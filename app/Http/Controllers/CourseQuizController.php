<?php

namespace App\Http\Controllers;

use App\Models\CourseQuizQuestion;
use App\Models\CourseSessionItem;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CourseQuizController extends Controller
{
    public function edit(Request $request, CourseSessionItem $item): View
    {
        $this->ensureCanManage($request);
        $this->ensureQuizItem($item);

        $item->load([
            'session.week.course.category',
            'session.week.course.subcategory',
            'quizQuestions',
        ]);

        return view('courses.quiz-editor', [
            'item' => $item,
            'course' => $item->session?->week?->course,
            'questionRows' => $this->questionRows($item),
            'questionTypeOptions' => CourseQuizQuestion::typeOptions(),
        ]);
    }

    public function update(Request $request, CourseSessionItem $item): RedirectResponse
    {
        $this->ensureCanManage($request);
        $this->ensureQuizItem($item);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'content' => ['nullable', 'string', 'max:2000'],
            'quiz_pass_percentage' => ['required', 'integer', 'min:1', 'max:100'],
            'quiz_max_attempts' => ['required', 'integer', 'min:1', 'max:50'],
            'quiz_time_limit_minutes' => ['nullable', 'integer', 'min:1', 'max:600'],
            'questions' => ['nullable', 'array'],
            'questions.*.id' => ['nullable', 'integer'],
            'questions.*.prompt' => ['nullable', 'string', 'max:2000'],
            'questions.*.question_type' => ['nullable', Rule::in(CourseQuizQuestion::TYPES)],
            'questions.*.points' => ['nullable', 'integer', 'min:1', 'max:100'],
            'questions.*.explanation' => ['nullable', 'string', 'max:2000'],
            'questions.*.option_1' => ['nullable', 'string', 'max:255'],
            'questions.*.option_2' => ['nullable', 'string', 'max:255'],
            'questions.*.option_3' => ['nullable', 'string', 'max:255'],
            'questions.*.option_4' => ['nullable', 'string', 'max:255'],
            'questions.*.correct_option' => ['nullable', 'integer', 'between:1,4'],
            'questions.*.correct_true_false' => ['nullable', Rule::in(['true', 'false'])],
            'questions.*.accepted_answers' => ['nullable', 'string', 'max:2000'],
            'questions.*.remove' => ['nullable', 'boolean'],
        ]);

        $sanitizedQuestions = $this->sanitizeQuestionPayloads(
            $item,
            $validated['questions'] ?? []
        );

        if ($sanitizedQuestions['active'] === []) {
            throw ValidationException::withMessages([
                'questions' => 'Add at least one quiz question before saving the quiz.',
            ]);
        }

        DB::transaction(function () use ($item, $validated, $sanitizedQuestions): void {
            $item->update([
                'title' => $validated['title'],
                'content' => $validated['content'] ?? null,
                'quiz_pass_percentage' => (int) $validated['quiz_pass_percentage'],
                'quiz_max_attempts' => (int) $validated['quiz_max_attempts'],
                'quiz_time_limit_minutes' => filled($validated['quiz_time_limit_minutes'] ?? null)
                    ? (int) $validated['quiz_time_limit_minutes']
                    : null,
            ]);

            if ($sanitizedQuestions['delete_ids'] !== []) {
                $item->quizQuestions()->whereIn('id', $sanitizedQuestions['delete_ids'])->delete();
            }

            foreach ($sanitizedQuestions['active'] as $position => $payload) {
                $attributes = [
                    'question_type' => $payload['question_type'],
                    'prompt' => $payload['prompt'],
                    'options' => $payload['options'],
                    'correct_answer' => $payload['correct_answer'],
                    'accepted_answers' => $payload['accepted_answers'],
                    'points' => $payload['points'],
                    'position' => $position + 1,
                    'explanation' => $payload['explanation'],
                ];

                if (! empty($payload['id'])) {
                    $item->quizQuestions()
                        ->whereKey($payload['id'])
                        ->update($attributes);

                    continue;
                }

                $item->quizQuestions()->create($attributes);
            }
        });

        return redirect()
            ->route('course-session-items.quiz.edit', $item)
            ->with('success', 'Quiz settings and questions saved.');
    }

    private function ensureCanManage(Request $request): void
    {
        abort_unless(
            in_array($request->user()?->role, [User::ROLE_SUPERADMIN, User::ROLE_ADMIN], true),
            403
        );
    }

    private function ensureQuizItem(CourseSessionItem $item): void
    {
        abort_unless($item->item_type === CourseSessionItem::TYPE_QUIZ, 404);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function questionRows(CourseSessionItem $item): array
    {
        $rows = $item->quizQuestions->map(function (CourseQuizQuestion $question): array {
            $options = array_pad($question->optionList(), 4, '');
            $correctOption = $question->question_type === CourseQuizQuestion::TYPE_SINGLE_CHOICE
                ? (int) $question->correct_answer
                : null;

            return [
                'id' => $question->id,
                'prompt' => $question->prompt,
                'question_type' => $question->question_type,
                'points' => $question->points,
                'explanation' => $question->explanation,
                'option_1' => $options[0] ?? '',
                'option_2' => $options[1] ?? '',
                'option_3' => $options[2] ?? '',
                'option_4' => $options[3] ?? '',
                'correct_option' => $correctOption,
                'correct_true_false' => $question->question_type === CourseQuizQuestion::TYPE_TRUE_FALSE
                    ? $question->correct_answer
                    : null,
                'accepted_answers' => implode(PHP_EOL, $question->acceptedAnswerList()),
                'remove' => false,
            ];
        })->values()->all();

        if ($rows === []) {
            $rows[] = $this->blankQuestionRow();
        }

        return $rows;
    }

    /**
     * @param  array<int, array<string, mixed>>  $questionPayloads
     * @return array{active: array<int, array<string, mixed>>, delete_ids: array<int, int>}
     */
    private function sanitizeQuestionPayloads(CourseSessionItem $item, array $questionPayloads): array
    {
        $existingIds = $item->quizQuestions()->pluck('id')->map(fn ($id): int => (int) $id)->all();
        $deleteIds = [];
        $activeQuestions = [];

        foreach (array_values($questionPayloads) as $payload) {
            $questionId = isset($payload['id']) ? (int) $payload['id'] : null;
            $remove = filter_var($payload['remove'] ?? false, FILTER_VALIDATE_BOOL);

            if ($questionId && ! in_array($questionId, $existingIds, true)) {
                throw ValidationException::withMessages([
                    'questions' => 'One of the quiz questions no longer belongs to this quiz. Refresh and try again.',
                ]);
            }

            if ($remove) {
                if ($questionId) {
                    $deleteIds[] = $questionId;
                }

                continue;
            }

            $prompt = trim((string) ($payload['prompt'] ?? ''));
            $questionType = (string) ($payload['question_type'] ?? '');
            $points = max(1, (int) ($payload['points'] ?? 1));
            $explanation = trim((string) ($payload['explanation'] ?? ''));

            $hasVisibleContent = $prompt !== ''
                || $questionType !== ''
                || trim((string) ($payload['option_1'] ?? '')) !== ''
                || trim((string) ($payload['option_2'] ?? '')) !== ''
                || trim((string) ($payload['option_3'] ?? '')) !== ''
                || trim((string) ($payload['option_4'] ?? '')) !== ''
                || trim((string) ($payload['accepted_answers'] ?? '')) !== '';

            if (! $hasVisibleContent) {
                continue;
            }

            if ($prompt === '' || ! in_array($questionType, CourseQuizQuestion::TYPES, true)) {
                throw ValidationException::withMessages([
                    'questions' => 'Each quiz question needs a prompt and a valid type.',
                ]);
            }

            $options = null;
            $correctAnswer = null;
            $acceptedAnswers = null;

            if ($questionType === CourseQuizQuestion::TYPE_SINGLE_CHOICE) {
                $options = collect([
                    $payload['option_1'] ?? null,
                    $payload['option_2'] ?? null,
                    $payload['option_3'] ?? null,
                    $payload['option_4'] ?? null,
                ])
                    ->map(fn ($option) => trim((string) $option))
                    ->filter(fn (string $option): bool => $option !== '')
                    ->values()
                    ->all();

                $correctOption = (int) ($payload['correct_option'] ?? 0);

                if (count($options) < 2 || $correctOption < 1 || $correctOption > count($options)) {
                    throw ValidationException::withMessages([
                        'questions' => 'Single choice questions need at least two options and a valid correct option.',
                    ]);
                }

                $correctAnswer = (string) $correctOption;
            }

            if ($questionType === CourseQuizQuestion::TYPE_TRUE_FALSE) {
                $correctTrueFalse = (string) ($payload['correct_true_false'] ?? '');

                if (! in_array($correctTrueFalse, ['true', 'false'], true)) {
                    throw ValidationException::withMessages([
                        'questions' => 'True/False questions need a correct answer.',
                    ]);
                }

                $correctAnswer = $correctTrueFalse;
            }

            if ($questionType === CourseQuizQuestion::TYPE_SHORT_ANSWER) {
                $acceptedAnswers = collect(preg_split('/\r\n|\r|\n/', (string) ($payload['accepted_answers'] ?? '')) ?: [])
                    ->map(fn ($answer) => trim((string) $answer))
                    ->filter(fn (string $answer): bool => $answer !== '')
                    ->values()
                    ->all();

                if ($acceptedAnswers === []) {
                    throw ValidationException::withMessages([
                        'questions' => 'Short answer questions need at least one accepted answer.',
                    ]);
                }
            }

            $activeQuestions[] = [
                'id' => $questionId,
                'prompt' => $prompt,
                'question_type' => $questionType,
                'points' => $points,
                'explanation' => $explanation !== '' ? $explanation : null,
                'options' => $options,
                'correct_answer' => $correctAnswer,
                'accepted_answers' => $acceptedAnswers,
            ];
        }

        return [
            'active' => $activeQuestions,
            'delete_ids' => array_values(array_unique($deleteIds)),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function blankQuestionRow(): array
    {
        return [
            'id' => null,
            'prompt' => '',
            'question_type' => CourseQuizQuestion::TYPE_SINGLE_CHOICE,
            'points' => 1,
            'explanation' => '',
            'option_1' => '',
            'option_2' => '',
            'option_3' => '',
            'option_4' => '',
            'correct_option' => 1,
            'correct_true_false' => 'true',
            'accepted_answers' => '',
            'remove' => false,
        ];
    }
}
