<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CourseItemSubmission;
use App\Models\CourseProgress;
use App\Models\CourseSessionItem;
use App\Models\User;
use App\Notifications\QuizLiveNotification;
use App\Notifications\SubmissionReceivedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CourseItemSubmissionController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeSubmissionManager($request);

        $requestedCourseId = (string) $request->query('course_id');
        $requestedTrainerId = (string) $request->query('trainer_id');
        $requestedStatus = (string) $request->query('status');
        $statusOptions = CourseItemSubmission::reviewStatusOptions();

        $courseId = $requestedCourseId !== '' ? (int) $requestedCourseId : null;
        $trainerId = $requestedTrainerId !== '' ? (int) $requestedTrainerId : null;
        $statusFilter = array_key_exists($requestedStatus, $statusOptions) ? $requestedStatus : null;

        $latestSubmissionIds = CourseItemSubmission::query()
            ->when($courseId, fn ($q) => $q->whereHas('enrollment', fn ($eq) => $eq->where('course_id', $courseId)))
            ->when($trainerId, fn ($q) => $q->whereHas('enrollment', fn ($eq) => $eq->where('trainer_id', $trainerId)))
            ->when($statusFilter, fn ($q) => $q->where('review_status', $statusFilter))
            ->selectRaw('MAX(id) as id')
            ->groupBy('course_enrollment_id', 'course_session_item_id')
            ->pluck('id');

        $submissions = CourseItemSubmission::with([
            'enrollment.course:id,title',
            'enrollment.student:id,name,email',
            'enrollment.trainer:id,name',
            'item:id,title,course_session_id,item_type',
            'item.session:id,course_week_id,title,session_number',
            'item.session.week:id,course_id,week_number',
            'quizAnswers.question',
            'reviewer:id,name',
        ])
            ->when(
                $latestSubmissionIds->isNotEmpty(),
                fn ($q) => $q->whereIn('id', $latestSubmissionIds),
                fn ($q) => $q->whereRaw('1 = 0')
            )
            ->orderByDesc('submitted_at')
            ->orderByDesc('id')
            ->paginate(8)
            ->withQueryString();

        return view('submissions.index', [
            'submissions' => $submissions,
            'courses' => Course::orderBy('title')->get(['id', 'title']),
            'trainers' => User::where('role', User::ROLE_TRAINER)->orderBy('name')->get(['id', 'name']),
            'statusOptions' => $statusOptions,
            'activeCourseId' => $courseId,
            'activeTrainerId' => $trainerId,
            'activeStatus' => $statusFilter,
        ]);
    }

    public function trainerIndex(Request $request): View
    {
        $trainer = $request->user();
        abort_unless($trainer?->role === User::ROLE_TRAINER, 403);

        $requestedCourseId = (string) $request->query('course_id');
        $requestedStatus = (string) $request->query('status');
        $studentSearch = trim((string) $request->query('student'));
        $statusOptions = CourseItemSubmission::reviewStatusOptions();

        $courseId = $requestedCourseId !== '' ? (int) $requestedCourseId : null;
        $statusFilter = array_key_exists($requestedStatus, $statusOptions) ? $requestedStatus : null;

        $trainerEnrollmentIds = CourseEnrollment::query()
            ->where('trainer_id', $trainer->id)
            ->when($courseId, fn ($q) => $q->where('course_id', $courseId))
            ->when($studentSearch !== '', function ($q) use ($studentSearch) {
                $q->whereHas('student', function ($studentQuery) use ($studentSearch) {
                    $studentQuery
                        ->where('name', 'like', '%'.$studentSearch.'%')
                        ->orWhere('email', 'like', '%'.$studentSearch.'%');
                });
            })
            ->pluck('id');

        $latestSubmissionIds = CourseItemSubmission::query()
            ->when(
                $trainerEnrollmentIds->isNotEmpty(),
                fn ($q) => $q->whereIn('course_enrollment_id', $trainerEnrollmentIds),
                fn ($q) => $q->whereRaw('1 = 0')
            )
            ->when($statusFilter, fn ($q) => $q->where('review_status', $statusFilter))
            ->selectRaw('MAX(id) as id')
            ->groupBy('course_enrollment_id', 'course_session_item_id')
            ->pluck('id');

        $summaryQuery = CourseItemSubmission::query()
            ->when(
                $latestSubmissionIds->isNotEmpty(),
                fn ($q) => $q->whereIn('id', $latestSubmissionIds),
                fn ($q) => $q->whereRaw('1 = 0')
            );

        $summary = [
            'pending_review' => (clone $summaryQuery)
                ->where('review_status', CourseItemSubmission::STATUS_PENDING_REVIEW)
                ->count(),
            'reviewed' => (clone $summaryQuery)
                ->where('review_status', CourseItemSubmission::STATUS_REVIEWED)
                ->count(),
            'revision_requested' => (clone $summaryQuery)
                ->where('review_status', CourseItemSubmission::STATUS_REVISION_REQUESTED)
                ->count(),
        ];

        $submissions = CourseItemSubmission::with([
            'enrollment.course:id,title',
            'enrollment.student:id,name,email',
            'item:id,title,course_session_id,item_type',
            'item.session:id,course_week_id,title,session_number',
            'item.session.week:id,course_id,week_number',
            'quizAnswers.question',
            'reviewer:id,name',
        ])
            ->when(
                $latestSubmissionIds->isNotEmpty(),
                fn ($q) => $q->whereIn('id', $latestSubmissionIds),
                fn ($q) => $q->whereRaw('1 = 0')
            )
            ->orderByRaw(sprintf(
                "CASE review_status WHEN '%s' THEN 0 WHEN '%s' THEN 1 ELSE 2 END",
                CourseItemSubmission::STATUS_PENDING_REVIEW,
                CourseItemSubmission::STATUS_REVISION_REQUESTED
            ))
            ->orderByDesc('submitted_at')
            ->orderByDesc('id')
            ->paginate(8)
            ->withQueryString();

        return view('trainer.submissions', [
            'submissions' => $submissions,
            'courses' => Course::whereHas('enrollments', fn ($q) => $q->where('trainer_id', $trainer->id))
                ->orderBy('title')
                ->get(['id', 'title']),
            'statusOptions' => $statusOptions,
            'summary' => $summary,
            'activeCourseId' => $courseId,
            'activeStatus' => $statusFilter,
            'activeStudentSearch' => $studentSearch,
        ]);
    }

    public function store(Request $request, CourseSessionItem $item): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user?->role === User::ROLE_STUDENT, 403);

        abort_unless(in_array($item->item_type, [CourseSessionItem::TYPE_TASK, CourseSessionItem::TYPE_QUIZ], true), 404);

        $courseId = (int) optional(optional($item->session)->week)->course_id;
        abort_unless($courseId > 0, 404);

        $enrollment = CourseEnrollment::where('course_id', $courseId)
            ->where('student_id', $user->id)
            ->first();

        abort_unless($enrollment, 403, 'You can submit only for enrolled courses.');

        if ($item->item_type === CourseSessionItem::TYPE_QUIZ && ! $item->is_live) {
            throw ValidationException::withMessages([
                'quiz' => 'This quiz is not live yet.',
            ]);
        }

        if ($item->item_type === CourseSessionItem::TYPE_TASK) {
            return $this->storeTaskSubmission($request, $item, $enrollment, $user->id);
        }

        return $this->storeQuizSubmission($request, $item, $enrollment, $user->id);
    }

    public function download(Request $request, CourseItemSubmission $submission)
    {
        $user = $request->user();
        abort_unless($user, 403);

        $enrollment = $submission->enrollment;
        $courseId = (int) $enrollment?->course_id;
        abort_unless($courseId > 0, 404);

        if (in_array($user->role, [User::ROLE_SUPERADMIN, User::ROLE_ADMIN, User::ROLE_MANAGER_HR, User::ROLE_IT], true)) {
            return $this->downloadFile($submission);
        }

        if ($user->role === User::ROLE_STUDENT && $submission->submitted_by === $user->id) {
            return $this->downloadFile($submission);
        }

        if ($user->role === User::ROLE_TRAINER && (int) $enrollment->trainer_id === (int) $user->id) {
            return $this->downloadFile($submission);
        }

        abort(403);
    }

    public function itemSubmissions(Request $request, CourseSessionItem $item): View
    {
        $user = $request->user();
        abort_unless($user?->role === User::ROLE_TRAINER, 403);

        abort_unless(in_array($item->item_type, [CourseSessionItem::TYPE_TASK, CourseSessionItem::TYPE_QUIZ], true), 404);

        $courseId = (int) optional(optional($item->session)->week)->course_id;
        abort_unless($courseId > 0, 404);

        $assignedEnrollments = CourseEnrollment::with('student')
            ->where('course_id', $courseId)
            ->where('trainer_id', $user->id)
            ->get();

        $latestSubmissions = CourseItemSubmission::with(['quizAnswers.question', 'submitter', 'reviewer'])
            ->whereIn('course_enrollment_id', $assignedEnrollments->pluck('id'))
            ->where('course_session_item_id', $item->id)
            ->latest('submitted_at')
            ->get()
            ->groupBy('course_enrollment_id')
            ->map->first();

        $rows = $assignedEnrollments->map(function (CourseEnrollment $enrollment) use ($latestSubmissions) {
            $submission = $latestSubmissions->get($enrollment->id);

            return [
                'enrollment' => $enrollment,
                'submission' => $submission,
            ];
        });

        return view('trainer.item-submissions', [
            'item' => $item,
            'rows' => $rows,
        ]);
    }

    public function review(Request $request, CourseItemSubmission $submission): RedirectResponse
    {
        $user = $request->user();

        abort_unless($this->canReviewSubmission($user, $submission), 403);

        $submission->loadMissing('item');
        $isQuizSubmission = $submission->submission_type === CourseSessionItem::TYPE_QUIZ;

        $data = $request->validate([
            'review_status' => ['required', Rule::in(CourseItemSubmission::REVIEW_STATUSES)],
            'review_notes' => [
                Rule::requiredIf(
                    fn () => $request->input('review_status') === CourseItemSubmission::STATUS_REVISION_REQUESTED
                ),
                'nullable',
                'string',
                'max:4000',
            ],
            'score_earned' => ['nullable', 'integer', 'min:0'],
        ]);

        $hasNewerSubmission = CourseItemSubmission::query()
            ->where('course_enrollment_id', $submission->course_enrollment_id)
            ->where('course_session_item_id', $submission->course_session_item_id)
            ->where('id', '>', $submission->id)
            ->exists();

        if ($hasNewerSubmission) {
            return back()->withErrors([
                'submission' => 'Only the latest submission can be reviewed. Refresh the page and try again.',
            ]);
        }

        $reviewStatus = (string) $data['review_status'];
        $scoreEarned = null;
        $scorePercent = null;
        $passed = null;

        if ($isQuizSubmission) {
            $scoreTotal = max(0, (int) ($submission->score_total ?? 0));
            $scoreEarned = $request->filled('score_earned')
                ? (int) $request->input('score_earned')
                : (int) ($submission->score_earned ?? 0);

            if ($scoreTotal > 0 && $scoreEarned > $scoreTotal) {
                throw ValidationException::withMessages([
                    'score_earned' => 'Score cannot be higher than the total quiz points.',
                ]);
            }

            $scorePercent = $this->scorePercent($scoreEarned, $scoreTotal);
            $passed = $this->resolveQuizPassed($submission, $reviewStatus, $scorePercent);
        }

        $submission->update([
            'review_status' => $reviewStatus,
            'review_notes' => $reviewStatus === CourseItemSubmission::STATUS_PENDING_REVIEW
                ? null
                : ($data['review_notes'] ?: null),
            'reviewed_by' => $reviewStatus === CourseItemSubmission::STATUS_PENDING_REVIEW ? null : $user->id,
            'reviewed_at' => $reviewStatus === CourseItemSubmission::STATUS_PENDING_REVIEW ? null : now(),
            'score_earned' => $isQuizSubmission ? $scoreEarned : $submission->score_earned,
            'score_percent' => $isQuizSubmission ? $scorePercent : $submission->score_percent,
            'passed' => $isQuizSubmission ? $passed : $submission->passed,
        ]);

        $progress = CourseProgress::firstOrCreate(
            [
                'course_enrollment_id' => $submission->course_enrollment_id,
                'course_session_item_id' => $submission->course_session_item_id,
            ],
            [
                'completed_at' => null,
            ]
        );

        $progress->completed_at = $reviewStatus === CourseItemSubmission::STATUS_REVISION_REQUESTED
            || ($isQuizSubmission && $passed !== true)
            ? null
            : ($progress->completed_at ?? $submission->submitted_at ?? now());
        $progress->save();

        $message = match ($reviewStatus) {
            CourseItemSubmission::STATUS_REVIEWED => $isQuizSubmission && $scorePercent !== null
                ? 'Quiz reviewed at '.$scorePercent.'%.'
                : 'Submission marked as reviewed.',
            CourseItemSubmission::STATUS_REVISION_REQUESTED => 'Revision requested successfully.',
            default => 'Submission moved back to pending review.',
        };

        return back()->with('success', $message);
    }

    public function toggleQuizLive(Request $request, CourseSessionItem $item): RedirectResponse
    {
        $user = $request->user();
        abort_unless(in_array($user?->role, [User::ROLE_TRAINER, User::ROLE_ADMIN, User::ROLE_SUPERADMIN], true), 403);

        abort_unless($item->item_type === CourseSessionItem::TYPE_QUIZ, 404);

        $courseId = (int) optional(optional($item->session)->week)->course_id;
        abort_unless($courseId > 0, 404);

        if ($user->role === User::ROLE_TRAINER) {
            $isAssigned = CourseEnrollment::where('course_id', $courseId)
                ->where('trainer_id', $user->id)
                ->exists();
            abort_unless($isAssigned, 403);
        }

        if (! $item->is_live && $item->quizQuestions()->doesntExist()) {
            return back()->withErrors([
                'quiz' => 'Add at least one quiz question before going live.',
            ]);
        }

        $item->is_live = ! $item->is_live;
        $item->live_at = $item->is_live ? now() : null;
        $item->save();

        if ($item->is_live && $this->notificationsTableAvailable()) {
            $studentIds = CourseEnrollment::where('course_id', $courseId)
                ->when(
                    $user->role === User::ROLE_TRAINER,
                    fn ($q) => $q->where('trainer_id', $user->id)
                )
                ->pluck('student_id')
                ->unique();

            User::whereIn('id', $studentIds)->get()
                ->each(fn (User $student) => $student->notify(new QuizLiveNotification($item, $user)));
        }

        return back()->with('success', $item->is_live ? 'Quiz is live now.' : 'Quiz has been closed.');
    }

    private function notificationsTableAvailable(): bool
    {
        return Schema::hasTable('notifications');
    }

    private function authorizeSubmissionManager(Request $request): void
    {
        abort_unless(
            in_array($request->user()?->role, [User::ROLE_SUPERADMIN, User::ROLE_ADMIN], true),
            403
        );
    }

    private function canReviewSubmission(?User $user, CourseItemSubmission $submission): bool
    {
        if (! $user) {
            return false;
        }

        if (in_array($user->role, [User::ROLE_SUPERADMIN, User::ROLE_ADMIN], true)) {
            return true;
        }

        return $user->role === User::ROLE_TRAINER
            && (int) $submission->enrollment?->trainer_id === (int) $user->id;
    }

    private function storeTaskSubmission(
        Request $request,
        CourseSessionItem $item,
        CourseEnrollment $enrollment,
        int $userId
    ): RedirectResponse {
        $data = $request->validate([
            'submission_file' => ['required', 'file', 'max:307200'],
        ]);

        $file = $request->file('submission_file');
        $safeName = preg_replace('/[^a-zA-Z0-9._-]+/', '-', $file->getClientOriginalName()) ?: 'submission';
        $path = $file->storeAs(
            'task-submissions/'.$enrollment->id.'/'.$item->id,
            uniqid('submission_', true).'-'.$safeName
        );

        $submission = CourseItemSubmission::create([
            'course_enrollment_id' => $enrollment->id,
            'course_session_item_id' => $item->id,
            'submitted_by' => $userId,
            'submission_type' => $item->item_type,
            'submitted_at' => now(),
            'review_status' => CourseItemSubmission::STATUS_PENDING_REVIEW,
            'reviewed_by' => null,
            'reviewed_at' => null,
            'review_notes' => null,
            'attempt_number' => 1,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_mime' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
        ]);

        CourseProgress::updateOrCreate(
            [
                'course_enrollment_id' => $enrollment->id,
                'course_session_item_id' => $item->id,
            ],
            [
                'completed_at' => now(),
            ]
        );

        if ($enrollment->trainer && $this->notificationsTableAvailable()) {
            $enrollment->trainer->notify(new SubmissionReceivedNotification($submission));
        }

        return back()->with('success', 'Submission uploaded successfully.');
    }

    private function storeQuizSubmission(
        Request $request,
        CourseSessionItem $item,
        CourseEnrollment $enrollment,
        int $userId
    ): RedirectResponse {
        $questions = $item->quizQuestions()->get();

        if ($questions->isEmpty()) {
            throw ValidationException::withMessages([
                'quiz' => 'This quiz does not have any questions yet. Contact your trainer or admin.',
            ]);
        }

        $attemptCount = CourseItemSubmission::query()
            ->where('course_enrollment_id', $enrollment->id)
            ->where('course_session_item_id', $item->id)
            ->where('submission_type', CourseSessionItem::TYPE_QUIZ)
            ->count();

        if ($attemptCount >= $item->quizMaxAttempts()) {
            throw ValidationException::withMessages([
                'quiz' => 'You have reached the maximum number of attempts for this quiz.',
            ]);
        }

        $data = $request->validate($this->buildQuizValidationRules($questions->all()));
        $submittedAnswers = $data['quiz_answers'] ?? [];
        $submittedAt = now();
        $attemptNumber = $attemptCount + 1;
        $scoreTotal = (int) $questions->sum('points');
        $scoreEarned = 0;
        $answerRows = [];

        foreach ($questions as $question) {
            $submittedAnswer = trim((string) ($submittedAnswers[$question->id] ?? ''));
            $isCorrect = $question->answerIsCorrect($submittedAnswer);
            $earnedPoints = $isCorrect ? (int) $question->points : 0;
            $scoreEarned += $earnedPoints;

            $answerRows[] = [
                'course_quiz_question_id' => $question->id,
                'answer_text' => $submittedAnswer,
                'is_correct' => $isCorrect,
                'earned_points' => $earnedPoints,
                'max_points' => (int) $question->points,
            ];
        }

        $scorePercent = $this->scorePercent($scoreEarned, $scoreTotal);
        $passed = $scorePercent !== null && $scorePercent >= $item->quizPassPercentage();
        $reviewStatus = $passed
            ? CourseItemSubmission::STATUS_REVIEWED
            : CourseItemSubmission::STATUS_REVISION_REQUESTED;
        $attemptsRemaining = max(0, $item->quizMaxAttempts() - $attemptNumber);
        $reviewNotes = $passed
            ? 'Auto-graded quiz. You passed this attempt.'
            : 'Auto-graded quiz. Score is below the pass mark, so another attempt is needed.';

        $submission = DB::transaction(function () use (
            $answerRows,
            $attemptNumber,
            $enrollment,
            $item,
            $passed,
            $reviewNotes,
            $reviewStatus,
            $scoreEarned,
            $scorePercent,
            $scoreTotal,
            $submittedAt,
            $userId
        ) {
            $submission = CourseItemSubmission::create([
                'course_enrollment_id' => $enrollment->id,
                'course_session_item_id' => $item->id,
                'submitted_by' => $userId,
                'submission_type' => CourseSessionItem::TYPE_QUIZ,
                'answer_text' => 'Structured quiz attempt submitted.',
                'submitted_at' => $submittedAt,
                'review_status' => $reviewStatus,
                'reviewed_by' => null,
                'reviewed_at' => $submittedAt,
                'review_notes' => $reviewNotes,
                'score_earned' => $scoreEarned,
                'score_total' => $scoreTotal,
                'score_percent' => $scorePercent,
                'passed' => $passed,
                'attempt_number' => $attemptNumber,
            ]);

            foreach ($answerRows as $answerRow) {
                $submission->quizAnswers()->create($answerRow);
            }

            CourseProgress::updateOrCreate(
                [
                    'course_enrollment_id' => $enrollment->id,
                    'course_session_item_id' => $item->id,
                ],
                [
                    'completed_at' => $passed ? $submittedAt : null,
                ]
            );

            return $submission;
        });

        if ($enrollment->trainer && $this->notificationsTableAvailable()) {
            $enrollment->trainer->notify(new SubmissionReceivedNotification($submission));
        }

        return back()->with(
            'success',
            $this->buildQuizAttemptMessage($scoreEarned, $scoreTotal, $scorePercent, $passed, $attemptsRemaining)
        );
    }

    /**
     * @param  array<int, \App\Models\CourseQuizQuestion>  $questions
     * @return array<string, array<int, mixed>>
     */
    private function buildQuizValidationRules(array $questions): array
    {
        $rules = [
            'quiz_answers' => ['required', 'array'],
        ];

        foreach ($questions as $question) {
            $field = 'quiz_answers.'.$question->id;

            $rules[$field] = match ($question->question_type) {
                \App\Models\CourseQuizQuestion::TYPE_SINGLE_CHOICE => [
                    'required',
                    Rule::in(array_map('strval', range(1, count($question->optionList())))),
                ],
                \App\Models\CourseQuizQuestion::TYPE_TRUE_FALSE => [
                    'required',
                    Rule::in(['true', 'false']),
                ],
                default => ['required', 'string', 'max:4000'],
            };
        }

        return $rules;
    }

    private function buildQuizAttemptMessage(
        int $scoreEarned,
        int $scoreTotal,
        ?int $scorePercent,
        bool $passed,
        int $attemptsRemaining
    ): string {
        $base = 'Quiz attempt recorded. Score '.$scoreEarned.' / '.$scoreTotal;

        if ($scorePercent !== null) {
            $base .= ' ('.$scorePercent.'%).';
        } else {
            $base .= '.';
        }

        if ($passed) {
            return $base.' You passed the quiz.';
        }

        return $attemptsRemaining > 0
            ? $base.' You can try again. Attempts left: '.$attemptsRemaining.'.'
            : $base.' You have no attempts left right now.';
    }

    private function resolveQuizPassed(
        CourseItemSubmission $submission,
        string $reviewStatus,
        ?int $scorePercent
    ): ?bool {
        if ($reviewStatus === CourseItemSubmission::STATUS_PENDING_REVIEW) {
            return null;
        }

        if ($reviewStatus === CourseItemSubmission::STATUS_REVISION_REQUESTED) {
            return false;
        }

        if ($scorePercent === null) {
            return true;
        }

        $passPercentage = $submission->item?->quizPassPercentage() ?? 70;

        return $scorePercent >= $passPercentage;
    }

    private function scorePercent(int $scoreEarned, int $scoreTotal): ?int
    {
        if ($scoreTotal < 1) {
            return null;
        }

        return (int) round(($scoreEarned / $scoreTotal) * 100);
    }

    private function downloadFile(CourseItemSubmission $submission)
    {
        if (! $submission->file_path || ! Storage::disk('local')->exists($submission->file_path)) {
            abort(404, 'Submission file not found.');
        }

        $filename = trim((string) $submission->file_name);
        if ($filename === '') {
            $filename = basename($submission->file_path) ?: 'submission';
        }
        $filename = $this->ensureExtension($filename, $submission->file_mime, $submission->file_path);
        return Storage::disk('local')->download($submission->file_path, $filename);
    }

    private function ensureExtension(string $filename, ?string $mime, string $path): string
    {
        $filename = trim($filename);
        if ($filename === '') {
            $filename = 'submission';
        }

        $currentExt = pathinfo($filename, PATHINFO_EXTENSION);
        if ($currentExt !== '') {
            return $filename;
        }

        $pathExt = pathinfo($path, PATHINFO_EXTENSION);
        $ext = $pathExt !== '' ? $pathExt : $this->extensionFromMime($mime);
        if ($ext !== '') {
            return $filename.'.'.$ext;
        }

        return $filename;
    }

    private function extensionFromMime(?string $mime): string
    {
        $mime = strtolower((string) $mime);

        return match ($mime) {
            'application/zip' => 'zip',
            'application/x-zip-compressed' => 'zip',
            'application/x-rar-compressed' => 'rar',
            'application/vnd.rar' => 'rar',
            'application/x-7z-compressed' => '7z',
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'application/vnd.ms-powerpoint' => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'video/mp4' => 'mp4',
            'video/quicktime' => 'mov',
            'video/x-msvideo' => 'avi',
            'video/x-matroska' => 'mkv',
            'text/plain' => 'txt',
            'text/csv' => 'csv',
            'application/rtf' => 'rtf',
            default => '',
        };
    }
}
