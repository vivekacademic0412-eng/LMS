<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseEnrollment;
use App\Models\CourseItemSubmission;
use App\Models\CourseProgress;
use App\Models\CourseSessionItem;
use App\Models\DemoFeatureVideo;
use App\Models\DemoReviewVideo;
use App\Models\DemoTask;
use App\Models\DemoTaskAssignment;
use App\Models\DemoTaskSubmission;
use App\Models\User;
use App\Services\StudentCertificateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        abort_unless($user, 403);

        return view('dashboard.index', $this->buildDashboardViewData($user));
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildDashboardViewData(User $user): array
    {
        if (! $user->is_active) {
            abort(403, 'Your account is inactive.');
        }

        $stats = Cache::remember('dashboard.stats', now()->addSeconds(60), function (): array {
            return [
                'users' => User::count(),
                'categories' => CourseCategory::count(),
                'courses' => Course::count(),
                'enrollments' => CourseEnrollment::count(),
                'active_users' => User::where('is_active', true)->count(),
                'inactive_users' => User::where('is_active', false)->count(),
                'students' => User::where('role', User::ROLE_STUDENT)->count(),
                'trainers' => User::where('role', User::ROLE_TRAINER)->count(),
                'live_quizzes' => Schema::hasTable('course_session_items') && Schema::hasColumn('course_session_items', 'is_live')
                    ? CourseSessionItem::where('item_type', CourseSessionItem::TYPE_QUIZ)
                        ->where('is_live', true)
                        ->count()
                    : 0,
                'pending_reviews' => $this->countLatestPendingReviews(),
                'completed_certificates' => $this->countCompletedCertificates(),
            ];
        });

        $studentResumeItem = null;
        $studentPendingActionItems = collect();
        $studentPendingActionSummary = [
            'tasks' => 0,
            'live_quizzes' => 0,
            'total' => 0,
        ];
        $studentRecentSubmissions = collect();
        $studentCertificates = collect();
        $studentAnalytics = $this->emptyStudentAnalytics();

        if ($user->role === User::ROLE_STUDENT) {
            $studentDashboard = Cache::remember(
                'dashboard.student.'.$user->id,
                now()->addSeconds(60),
                fn () => $this->resolveStudentDashboardData($user)
            );
            $learningItems = collect($studentDashboard['learningItems'] ?? []);
            $studentResumeItem = $studentDashboard['resumeItem'] ?? null;
            $studentPendingActionItems = collect($studentDashboard['pendingActionItems'] ?? []);
            $studentPendingActionSummary = array_merge(
                $studentPendingActionSummary,
                (array) ($studentDashboard['pendingActionSummary'] ?? [])
            );
            $studentRecentSubmissions = collect($studentDashboard['recentSubmissions'] ?? []);
            $studentCertificates = collect($studentDashboard['certificates'] ?? []);
            $studentAnalytics = is_array($studentDashboard['analytics'] ?? null)
                ? $studentDashboard['analytics']
                : $this->emptyStudentAnalytics();
        } else {
            $learningItems = Cache::remember(
                'dashboard.learning-items.'.$user->role.'.'.$user->id,
                now()->addSeconds(60),
                fn () => $this->resolveLearningItems($user)
            );
        }

        $heroCourse = $learningItems->sortByDesc('progress_percent')->first();
        if ($user->role === User::ROLE_STUDENT && $studentResumeItem) {
            $heroCourse = $learningItems->firstWhere('course_id', $studentResumeItem['course_id']) ?: $heroCourse;
        }
        $skillProgress = $learningItems
            ->groupBy('category')
            ->map(fn ($items, $category) => [
                'skill' => $category,
                'progress' => (int) round($items->avg('progress_percent')),
            ])
            ->values()
            ->take(5);

        $dashboardMode = $this->resolveDashboardMode($user);
        $overviewCards = $this->resolveOverviewCards($dashboardMode, $learningItems, $stats, $user);
        $quickActions = $this->resolveQuickActions($user);
        $panelDescription = $this->resolvePanelDescription($user);
        $panelStats = [
            'users' => $stats['users'],
            'categories' => $stats['categories'],
            'courses' => $stats['courses'],
        ];

        $recommendedCourses = Cache::remember('dashboard.recommended-courses', now()->addMinutes(5), function () {
            return Course::query()
                ->withEstimatedMinutesTotal()
                ->with(['category', 'creator'])
                ->latest('id')
                ->take(6)
                ->get()
                ->map(function (Course $course): array {
                    return [
                        'id' => $course->id,
                        'title' => $course->title,
                        'category' => $course->category?->name ?? 'General',
                        'provider' => $course->creator?->name ?? 'LMS Academy',
                        'hours' => max(1, (int) $course->duration_hours),
                        'estimated_duration' => $course->estimatedDurationLabel(),
                    ];
                });
        });

        $topics = Cache::remember('dashboard.topics', now()->addMinutes(5), function () {
            return CourseCategory::query()
                ->withCount('courses')
                ->orderByDesc('courses_count')
                ->take(8)
                ->get()
                ->map(fn (CourseCategory $category): array => [
                    'name' => $category->name,
                    'count' => (int) $category->courses_count,
                ]);
        });

        $notifications = collect();
        if (Schema::hasTable('notifications')) {
            $notifications = Cache::remember(
                'dashboard.notifications.'.$user->id,
                now()->addSeconds(30),
                fn () => $user->notifications()->latest()->take(5)->get()
            );
        }

        $assignedCourseIds = [];
        if ($user->role === User::ROLE_TRAINER) {
            $assignedCourseIds = Cache::remember(
                'dashboard.trainer.courses.'.$user->id,
                now()->addSeconds(60),
                fn () => CourseEnrollment::where('trainer_id', $user->id)->pluck('course_id')->all()
            );
        }

        $demoAssignments = collect();
        $demoCategories = collect();
        $demoFeatureVideos = collect();
        $demoReviewVideos = collect();
        $demoTasks = collect();
        $adminDemoSubmissions = collect();
        $demoTaskCooldownSeconds = DemoTaskSubmission::SHARED_DEMO_COOLDOWN_SECONDS;
        if ($dashboardMode === 'demo') {
            $userAssignments = Cache::remember(
                'dashboard.demo.assignments.'.$user->id,
                now()->addSeconds(30),
                fn () => DemoTaskAssignment::query()
                    ->with(['demoTask.creator:id,role'])
                    ->where('user_id', $user->id)
                    ->latest('assigned_at')
                    ->latest('id')
                    ->get()
            );

            $demoNow = now();
            $latestDemoSubmissions = DemoTaskSubmission::query()
                ->whereIn('demo_task_assignment_id', $userAssignments->pluck('id'))
                ->latest('submitted_at')
                ->latest('id')
                ->get()
                ->groupBy('demo_task_assignment_id')
                ->map->first();

            $demoAssignments = $userAssignments
                ->filter(fn (DemoTaskAssignment $assignment): bool => (bool) $assignment->demoTask)
                ->values()
                ->map(function (DemoTaskAssignment $assignment) use ($latestDemoSubmissions, $demoNow) {
                    $task = $assignment->demoTask;
                    $latestSubmission = $latestDemoSubmissions->get($assignment->id);
                    $cooldownRemainingSeconds = $latestSubmission?->sharedCooldownRemainingSeconds($demoNow) ?? 0;

                    return [
                        'assignment' => $assignment,
                        'task' => $task,
                        'submission' => $latestSubmission,
                        'is_in_cooldown' => $cooldownRemainingSeconds > 0,
                        'cooldown_remaining_seconds' => $cooldownRemainingSeconds,
                        'available_at' => $latestSubmission?->sharedCooldownEndsAt(),
                    ];
                });

            $demoTasks = $demoAssignments->pluck('task');

            $demoCategories = Cache::remember('dashboard.demo.categories', now()->addMinutes(5), function () {
                return CourseCategory::with([
                    'courses' => fn ($q) => $q->with(['category', 'subcategory'])->orderBy('title'),
                    'children.courses' => fn ($q) => $q->with(['category', 'subcategory'])->orderBy('title'),
                ])->whereNull('parent_id')->orderBy('name')->get();
            });

            $demoFeatureVideosQuery = DemoFeatureVideo::query();

            if (Schema::hasColumn('demo_feature_videos', 'position')) {
                $demoFeatureVideosQuery
                    ->orderByRaw('CASE WHEN position IS NULL THEN 1 ELSE 0 END')
                    ->orderBy('position')
                    ->orderByDesc('id');
            } else {
                $demoFeatureVideosQuery->latest('id');
            }

            $demoFeatureVideos = Cache::remember('dashboard.demo.feature-videos', now()->addMinutes(5), fn () => $demoFeatureVideosQuery->get());

            if (Schema::hasTable('demo_review_videos')) {
                $demoReviewVideos = Cache::remember('dashboard.demo.review-videos', now()->addMinutes(5), function () {
                    return DemoReviewVideo::query()
                        ->orderByRaw('CASE WHEN position IS NULL THEN 1 ELSE 0 END')
                        ->orderBy('position')
                        ->orderByDesc('id')
                        ->get();
                });
            }
        }

        if (in_array($dashboardMode, ['admin', 'viewer'], true)) {
            $demoTasks = Cache::remember('dashboard.demo.tasks', now()->addMinutes(5), fn () => DemoTask::withCount('assignments')->latest('id')->take(8)->get());
        }

        if ($dashboardMode === 'admin') {
            $adminDemoSubmissions = Cache::remember('dashboard.demo.submissions', now()->addSeconds(30), function () {
                return DemoTaskSubmission::query()
                    ->with([
                        'assignment.demoTask:id,title',
                        'assignment.user:id,name,email,role,is_active,created_at',
                        'assignment.assigner:id,name',
                    ])
                    ->latest('submitted_at')
                    ->take(6)
                    ->get()
                    ->filter(fn (DemoTaskSubmission $submission): bool => (bool) $submission->assignment?->user)
                    ->values();
            });
        }

        return compact(
            'user',
            'dashboardMode',
            'overviewCards',
            'quickActions',
            'panelDescription',
            'panelStats',
            'heroCourse',
            'learningItems',
            'skillProgress',
            'recommendedCourses',
            'topics',
            'notifications',
            'assignedCourseIds',
            'studentResumeItem',
            'studentPendingActionItems',
            'studentPendingActionSummary',
            'studentRecentSubmissions',
            'studentCertificates',
            'studentAnalytics',
            'demoAssignments',
            'demoCategories',
            'demoFeatureVideos',
            'demoReviewVideos',
            'demoTasks',
            'adminDemoSubmissions',
            'demoTaskCooldownSeconds'
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function emptyStudentAnalytics(): array
    {
        return [
            'stats' => [
                [
                    'label' => 'Courses',
                    'value' => 0,
                    'suffix' => '',
                    'decimals' => 0,
                    'caption' => 'enrolled right now',
                    'tone' => 'blue',
                ],
                [
                    'label' => 'Completion',
                    'value' => 0,
                    'suffix' => '%',
                    'decimals' => 0,
                    'caption' => 'overall progress',
                    'tone' => 'indigo',
                ],
                [
                    'label' => 'Certificates',
                    'value' => 0,
                    'suffix' => '',
                    'decimals' => 0,
                    'caption' => 'earned so far',
                    'tone' => 'green',
                ],
                [
                    'label' => 'Pending Submissions',
                    'value' => 0,
                    'suffix' => '',
                    'decimals' => 0,
                    'caption' => 'awaiting review or update',
                    'tone' => 'amber',
                ],
            ],
            'weekly' => [
                'default_view' => 'hours',
                'views' => [
                    'hours' => [
                        'label' => 'Hours spent',
                        'summary_label' => 'This week',
                        'summary_value' => 0.0,
                        'summary_suffix' => 'h',
                        'summary_decimals' => 1,
                        'secondary_label' => 'Daily average',
                        'secondary_value' => 0.0,
                        'secondary_suffix' => 'h',
                        'secondary_decimals' => 1,
                        'max' => 1.0,
                        'series' => collect(),
                    ],
                    'items' => [
                        'label' => 'Items completed',
                        'summary_label' => 'This week',
                        'summary_value' => 0,
                        'summary_suffix' => '',
                        'summary_decimals' => 0,
                        'secondary_label' => 'Active days',
                        'secondary_value' => 0,
                        'secondary_suffix' => '',
                        'secondary_decimals' => 0,
                        'max' => 1,
                        'series' => collect(),
                    ],
                    'quiz_scores' => [
                        'label' => 'Quiz scores',
                        'summary_label' => 'Average',
                        'summary_value' => 0,
                        'summary_suffix' => '%',
                        'summary_decimals' => 0,
                        'secondary_label' => 'Quiz days',
                        'secondary_value' => 0,
                        'secondary_suffix' => '',
                        'secondary_decimals' => 0,
                        'max' => 100,
                        'series' => collect(),
                    ],
                ],
            ],
            'completion' => [
                'overall_percent' => 0,
                'completed_courses' => 0,
                'in_progress_courses' => 0,
                'not_started_courses' => 0,
                'course_count' => 0,
                'completed_items' => 0,
                'total_items' => 0,
                'segments' => collect(),
                'series' => collect(),
            ],
            'radar' => [
                'series' => collect(),
                'goal_label' => 'Target = 100% completion across active categories',
            ],
            'heatmap' => [
                'weeks' => collect(),
                'active_days' => 0,
                'start_label' => '',
                'end_label' => '',
            ],
            'quiz' => [
                'average_score' => 0,
                'attempted' => 0,
                'reviewed' => 0,
                'pending' => 0,
                'revision_requested' => 0,
                'series' => collect(),
                'course_series' => collect(),
                'derived_from_reviews' => false,
            ],
            'streak' => [
                'current' => 0,
                'longest' => 0,
                'active_days' => 0,
                'tracker' => collect(),
                'tracker_active' => 0,
            ],
            'activity_feed' => collect(),
        ];
    }

    public function markAllNotificationsRead(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user && Schema::hasTable('notifications')) {
            $user->unreadNotifications()->update([
                'read_at' => now(),
            ]);
        }

        return back()->with('success', 'Notifications marked as read.');
    }

    public function markNotificationRead(Request $request, DatabaseNotification $notification): RedirectResponse
    {
        $user = $request->user();

        abort_unless(
            $user
            && $notification->notifiable_type === $user::class
            && (string) $notification->notifiable_id === (string) $user->getKey(),
            403
        );

        if (Schema::hasTable('notifications') && is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return back()->with('success', 'Notification marked as read.');
    }

    private function resolveDashboardMode(User $user): string
    {
        if ($user->role === User::ROLE_STUDENT) {
            return 'student';
        }

        if ($user->role === User::ROLE_TRAINER) {
            return 'trainer';
        }

        if ($user->role === User::ROLE_DEMO) {
            return 'demo';
        }

        if (in_array($user->role, [User::ROLE_SUPERADMIN, User::ROLE_ADMIN], true)) {
            return 'admin';
        }

        return 'viewer';
    }

    /**
     * @param \Illuminate\Support\Collection<int, array<string, mixed>> $learningItems
     * @param array<string, int> $stats
     * @return array<int, array<string, string|int|float>>
     */
    private function resolveOverviewCards(string $dashboardMode, $learningItems, array $stats, User $user): array
    {
        if ($dashboardMode === 'student') {
            return [
                ['code' => 'C', 'value' => $learningItems->count(), 'label' => 'Courses Enrolled'],
                ['code' => 'H', 'value' => round((float) $learningItems->sum('hours_done'), 1), 'label' => 'Total Learning Time', 'suffix' => 'h'],
                ['code' => 'T', 'value' => $learningItems->where('progress_percent', '>=', 100)->count(), 'label' => 'Certificates Earned'],
                ['code' => 'S', 'value' => max(2, min(14, 2 + $learningItems->where('progress_percent', '>=', 50)->count())), 'label' => 'Day Learning Streak'],
            ];
        }

        if ($dashboardMode === 'trainer') {
            $trainerStats = $this->resolveTrainerOverviewStats($user);

            return [
                ['code' => 'ST', 'value' => $trainerStats['assigned_students'], 'label' => 'Assigned Students'],
                ['code' => 'CR', 'value' => $trainerStats['assigned_courses'], 'label' => 'Assigned Courses'],
                ['code' => 'EN', 'value' => $trainerStats['active_enrollments'], 'label' => 'Active Enrollments'],
                ['code' => 'PQ', 'value' => $trainerStats['pending_reviews'], 'label' => 'Pending Review'],
                ['code' => 'RQ', 'value' => $trainerStats['revision_requested'], 'label' => 'Revision Requested'],
                ['code' => 'PR', 'value' => $trainerStats['avg_progress'], 'label' => 'Avg Learner Progress', 'suffix' => '%'],
            ];
        }

        if ($dashboardMode === 'admin') {
            return [
                ['code' => 'U', 'value' => $stats['users'], 'label' => 'Total Users'],
                ['code' => 'A', 'value' => $stats['active_users'], 'label' => 'Active Users'],
                ['code' => 'IU', 'value' => $stats['inactive_users'], 'label' => 'Inactive Users'],
                ['code' => 'EN', 'value' => $stats['enrollments'], 'label' => 'Enrollments'],
                ['code' => 'CR', 'value' => $stats['courses'], 'label' => 'Courses'],
                ['code' => 'PR', 'value' => $stats['pending_reviews'], 'label' => 'Pending Review'],
                ['code' => 'LQ', 'value' => $stats['live_quizzes'], 'label' => 'Live Quizzes'],
                ['code' => 'CC', 'value' => $stats['completed_certificates'], 'label' => 'Certificates'],
            ];
        }

        return [
            ['code' => 'CA', 'value' => $stats['categories'], 'label' => 'Categories'],
            ['code' => 'CR', 'value' => $stats['courses'], 'label' => 'Courses'],
            ['code' => 'ST', 'value' => $stats['students'], 'label' => 'Students'],
            ['code' => 'TR', 'value' => $stats['trainers'], 'label' => 'Trainers'],
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function resolveQuickActions(User $user): array
    {
        if (in_array($user->role, [User::ROLE_SUPERADMIN, User::ROLE_ADMIN], true)) {
            return [
                ['label' => 'User Control', 'route' => route('users.index')],
                ['label' => 'Enrollments', 'route' => route('enrollments.index')],
                ['label' => 'Submission Review', 'route' => route('submissions.index')],
                ['label' => 'Demo Tasks', 'route' => route('demo-tasks.create-page')],
                ['label' => 'Demo Task Assignments', 'route' => route('demo-tasks.assign-page')],
                ['label' => 'Demo Task Submissions', 'route' => route('demo-tasks.submissions-page')],
                ['label' => 'Broadcast Notifications', 'route' => route('broadcast-notifications.index')],
                ['label' => 'Categories', 'route' => route('course-categories.index')],
                ['label' => 'Courses', 'route' => route('courses.index')],
                ['label' => 'Demo Feature Video', 'route' => route('demo-feature-video.index')],
                ['label' => 'Reviews', 'route' => route('demo-review-videos.index')],
            ];
        }

        if ($user->role === User::ROLE_TRAINER) {
            return [
                ['label' => 'Review Queue', 'route' => route('trainer.submissions')],
                ['label' => 'Assigned Students', 'route' => route('trainer.assigned-students')],
                ['label' => 'Trainer Tracking', 'route' => route('trainer.progress')],
                ['label' => 'My Courses', 'route' => route('trainer.courses')],
            ];
        }

        if ($user->role === User::ROLE_DEMO) {
            return [];
        }

        if ($user->role === User::ROLE_STUDENT) {
            return [
                ['label' => 'My Courses', 'route' => route('student.courses')],
                ['label' => 'My History', 'route' => route('student.history')],
                ['label' => 'My Certificates', 'route' => route('student.certificates')],
                ['label' => 'Course Catalog', 'route' => route('courses.index')],
            ];
        }

        if ($user->role === User::ROLE_MANAGER_HR) {
            return [
                ['label' => 'HR Panel', 'route' => route('panel.manager_hr')],
                ['label' => 'Categories', 'route' => route('course-categories.index')],
                ['label' => 'Courses', 'route' => route('courses.index')],
            ];
        }

        if ($user->role === User::ROLE_IT) {
            return [
                ['label' => 'IT Panel', 'route' => route('panel.it')],
                ['label' => 'Categories', 'route' => route('course-categories.index')],
                ['label' => 'Courses', 'route' => route('courses.index')],
            ];
        }

        return [
            ['label' => 'Categories', 'route' => route('course-categories.index')],
            ['label' => 'Courses', 'route' => route('courses.index')],
        ];
    }

    private function resolvePanelDescription(User $user): string
    {
        return match ($user->role) {
            User::ROLE_SUPERADMIN => 'Full control across users, enrollments, categories, and courses.',
            User::ROLE_ADMIN => 'Operational control for users, enrollments, and learning data.',
            User::ROLE_MANAGER_HR, User::ROLE_IT => 'View-only access for catalog and reporting workflows.',
            User::ROLE_TRAINER => 'Track assigned students and monitor their progress.',
            User::ROLE_STUDENT => 'Access enrolled courses and continue course progress.',
            User::ROLE_DEMO => 'Demo account for task previews and sample catalog.',
            default => 'Role-restricted workspace.',
        };
    }

    /**
     * @return array{
     *     assigned_students:int,
     *     assigned_courses:int,
     *     active_enrollments:int,
     *     avg_progress:int,
     *     pending_reviews:int,
     *     revision_requested:int
     * }
     */
    private function resolveTrainerOverviewStats(User $trainer): array
    {
        $trainerEnrollments = CourseEnrollment::query()
            ->with(['progressItems:id,course_enrollment_id,completed_at'])
            ->where('trainer_id', $trainer->id)
            ->get(['id', 'course_id', 'student_id']);

        $courseIds = $trainerEnrollments->pluck('course_id')->unique()->filter()->values();
        $totalItemsByCourse = collect();

        if ($courseIds->isNotEmpty()) {
            $totalItemsByCourse = DB::table('course_session_items')
                ->join('course_sessions', 'course_sessions.id', '=', 'course_session_items.course_session_id')
                ->join('course_weeks', 'course_weeks.id', '=', 'course_sessions.course_week_id')
                ->whereIn('course_weeks.course_id', $courseIds)
                ->groupBy('course_weeks.course_id')
                ->select('course_weeks.course_id', DB::raw('count(course_session_items.id) as total_items'))
                ->pluck('total_items', 'course_weeks.course_id');
        }

        $avgProgress = (int) round((float) $trainerEnrollments
            ->map(function (CourseEnrollment $enrollment) use ($totalItemsByCourse): int {
                $totalItems = max(1, (int) ($totalItemsByCourse[$enrollment->course_id] ?? 1));
                $completedItems = min(
                    $totalItems,
                    $enrollment->progressItems->whereNotNull('completed_at')->count()
                );

                return (int) round(($completedItems / $totalItems) * 100);
            })
            ->avg());

        $reviewCounts = $this->resolveTrainerReviewCounts($trainer);

        return [
            'assigned_students' => $trainerEnrollments->pluck('student_id')->unique()->count(),
            'assigned_courses' => $courseIds->count(),
            'active_enrollments' => $trainerEnrollments->count(),
            'avg_progress' => $avgProgress,
            'pending_reviews' => $reviewCounts['pending_reviews'],
            'revision_requested' => $reviewCounts['revision_requested'],
        ];
    }

    /**
     * @return array{pending_reviews:int, revision_requested:int}
     */
    private function resolveTrainerReviewCounts(User $trainer): array
    {
        if (! Schema::hasTable('course_item_submissions') || ! Schema::hasColumn('course_item_submissions', 'review_status')) {
            return [
                'pending_reviews' => 0,
                'revision_requested' => 0,
            ];
        }

        $trainerEnrollmentIds = CourseEnrollment::query()
            ->where('trainer_id', $trainer->id)
            ->pluck('id');

        if ($trainerEnrollmentIds->isEmpty()) {
            return [
                'pending_reviews' => 0,
                'revision_requested' => 0,
            ];
        }

        $latestSubmissionIds = CourseItemSubmission::query()
            ->whereIn('course_enrollment_id', $trainerEnrollmentIds)
            ->selectRaw('MAX(id) as id')
            ->groupBy('course_enrollment_id', 'course_session_item_id')
            ->pluck('id');

        if ($latestSubmissionIds->isEmpty()) {
            return [
                'pending_reviews' => 0,
                'revision_requested' => 0,
            ];
        }

        return [
            'pending_reviews' => CourseItemSubmission::query()
                ->whereIn('id', $latestSubmissionIds)
                ->where('review_status', CourseItemSubmission::STATUS_PENDING_REVIEW)
                ->count(),
            'revision_requested' => CourseItemSubmission::query()
                ->whereIn('id', $latestSubmissionIds)
                ->where('review_status', CourseItemSubmission::STATUS_REVISION_REQUESTED)
                ->count(),
        ];
    }

    private function countLatestPendingReviews(): int
    {
        if (! Schema::hasTable('course_item_submissions') || ! Schema::hasColumn('course_item_submissions', 'review_status')) {
            return 0;
        }

        $latestSubmissionIds = CourseItemSubmission::query()
            ->selectRaw('MAX(id) as id')
            ->groupBy('course_enrollment_id', 'course_session_item_id')
            ->pluck('id');

        if ($latestSubmissionIds->isEmpty()) {
            return 0;
        }

        return CourseItemSubmission::query()
            ->whereIn('id', $latestSubmissionIds)
            ->where('review_status', CourseItemSubmission::STATUS_PENDING_REVIEW)
            ->count();
    }

    private function countCompletedCertificates(): int
    {
        $requiredTables = [
            'course_session_items',
            'course_sessions',
            'course_weeks',
            'course_progress',
            'course_enrollments',
        ];

        foreach ($requiredTables as $table) {
            if (! Schema::hasTable($table)) {
                return 0;
            }
        }

        $totalItemsByCourse = DB::table('course_session_items')
            ->join('course_sessions', 'course_sessions.id', '=', 'course_session_items.course_session_id')
            ->join('course_weeks', 'course_weeks.id', '=', 'course_sessions.course_week_id')
            ->groupBy('course_weeks.course_id')
            ->select('course_weeks.course_id', DB::raw('count(course_session_items.id) as total_items'))
            ->pluck('total_items', 'course_weeks.course_id');

        if ($totalItemsByCourse->isEmpty()) {
            return 0;
        }

        $completedByEnrollment = CourseProgress::query()
            ->whereNotNull('completed_at')
            ->groupBy('course_enrollment_id')
            ->select('course_enrollment_id', DB::raw('count(*) as completed_items'))
            ->pluck('completed_items', 'course_enrollment_id');

        return CourseEnrollment::query()
            ->get(['id', 'course_id'])
            ->filter(function (CourseEnrollment $enrollment) use ($totalItemsByCourse, $completedByEnrollment): bool {
                $totalItems = (int) ($totalItemsByCourse[$enrollment->course_id] ?? 0);

                if ($totalItems <= 0) {
                    return false;
                }

                $completedItems = min($totalItems, (int) ($completedByEnrollment[$enrollment->id] ?? 0));

                return $completedItems >= $totalItems;
            })
            ->count();
    }

    /**
     * @return array{
     *     learningItems: \Illuminate\Support\Collection<int, array<string, mixed>>,
     *     resumeItem: array<string, mixed>|null,
     *     pendingActionItems: \Illuminate\Support\Collection<int, array<string, mixed>>,
     *     pendingActionSummary: array{tasks:int, live_quizzes:int, total:int},
     *     recentSubmissions: \Illuminate\Support\Collection<int, array<string, mixed>>,
     *     certificates: \Illuminate\Support\Collection<int, array<string, mixed>>,
     *     analytics: array<string, mixed>
     * }
     */
    private function resolveStudentDashboardData(User $user): array
    {
        $palette = ['blue', 'green', 'violet', 'orange', 'red', 'teal'];
        $certificateService = app(StudentCertificateService::class);
        $enrollments = CourseEnrollment::query()
            ->where('student_id', $user->id)
            ->with(['course.category', 'course.weeks.sessions.items', 'progressItems'])
            ->latest('id')
            ->get();

        $learningItems = collect();
        $pendingActionItems = collect();
        $certificates = collect();
        $activityByDate = [];
        $activityFeed = collect();
        $overallCompletedItems = 0;
        $overallTotalItems = 0;

        foreach ($enrollments as $index => $enrollment) {
            $course = $enrollment->course;

            if (! $course) {
                continue;
            }

            $completedItemIds = $enrollment->progressItems
                ->whereNotNull('completed_at')
                ->pluck('course_session_item_id')
                ->map(fn ($id) => (int) $id)
                ->values()
                ->all();

            $courseItems = collect();
            foreach ($course->weeks as $week) {
                foreach ($week->sessions as $session) {
                    foreach ($session->items as $item) {
                        $courseItems->push([
                            'item_id' => (int) $item->id,
                            'title' => $item->title ?: 'Untitled Item',
                            'item_type' => $item->item_type,
                            'is_live' => (bool) $item->is_live,
                            'week_id' => (int) $week->id,
                            'week_number' => (int) ($week->week_number ?? 0),
                            'session_id' => (int) $session->id,
                            'session_number' => (int) ($session->session_number ?? 0),
                            'session_title' => $session->title ?: 'Session',
                            'item_type_label' => ucwords(str_replace('_', ' ', (string) $item->item_type)),
                            'route' => $this->buildStudentCourseItemRoute(
                                (int) $course->id,
                                (int) $week->id,
                                (int) $session->id,
                                (int) $item->id
                            ),
                        ]);
                    }
                }
            }

            $courseItemsById = $courseItems->keyBy('item_id');
            $totalItems = max(1, $courseItems->count());
            $overallTotalItems += $courseItems->count();
            $completedItems = min(
                $totalItems,
                $courseItems->whereIn('item_id', $completedItemIds)->count()
            );
            $overallCompletedItems += $completedItems;
            $progressPercent = (int) round(($completedItems / $totalItems) * 100);
            $hoursTotal = max(1, (int) ($course->duration_hours ?? 1));
            $hoursDone = round(($hoursTotal * $progressPercent) / 100, 1);
            $estimatedHoursPerItem = $courseItems->count() > 0
                ? round($hoursTotal / $courseItems->count(), 2)
                : 0.0;

            foreach ($enrollment->progressItems->whereNotNull('completed_at') as $progressRow) {
                $completedAt = $progressRow->completed_at;

                if (! $completedAt) {
                    continue;
                }

                $dateKey = $completedAt->toDateString();
                $activityByDate[$dateKey] = [
                    'activity_count' => (int) (($activityByDate[$dateKey]['activity_count'] ?? 0) + 1),
                    'estimated_hours' => round((float) (($activityByDate[$dateKey]['estimated_hours'] ?? 0.0) + $estimatedHoursPerItem), 2),
                    'items_completed' => (int) (($activityByDate[$dateKey]['items_completed'] ?? 0) + 1),
                    'quiz_score_total' => (int) ($activityByDate[$dateKey]['quiz_score_total'] ?? 0),
                    'quiz_score_count' => (int) ($activityByDate[$dateKey]['quiz_score_count'] ?? 0),
                ];

                $itemMeta = $courseItemsById->get((int) $progressRow->course_session_item_id);

                $activityFeed->push([
                    'timestamp' => (int) $completedAt->timestamp,
                    'status_label' => 'Completed',
                    'tone' => 'completed',
                    'title' => (string) ($itemMeta['title'] ?? 'Learning item completed'),
                    'description' => trim(((string) ($itemMeta['item_type_label'] ?? 'Learning item')).' completed in '.($course->title ?: 'Course')),
                    'meta' => trim(implode(' | ', array_filter([
                        ! empty($itemMeta['week_number']) ? 'Week '.$itemMeta['week_number'] : null,
                        ! empty($itemMeta['session_number']) ? 'Session '.$itemMeta['session_number'] : null,
                    ]))),
                    'occurred_at_human' => $completedAt->diffForHumans(),
                    'route' => (string) ($itemMeta['route'] ?? route('student.courses.show', $course)),
                    'route_label' => 'Open lesson',
                    'secondary_route' => route('student.courses.show', $course),
                    'secondary_label' => 'Course',
                ]);
            }

            $nextPendingItem = $courseItems->first(
                fn (array $courseItem) => ! in_array($courseItem['item_id'], $completedItemIds, true)
            );

            $pendingTaskCount = $courseItems->filter(
                fn (array $courseItem) => $courseItem['item_type'] === CourseSessionItem::TYPE_TASK
                    && ! in_array($courseItem['item_id'], $completedItemIds, true)
            )->count();

            $liveQuizCount = $courseItems->filter(
                fn (array $courseItem) => $courseItem['item_type'] === CourseSessionItem::TYPE_QUIZ
                    && $courseItem['is_live']
                    && ! in_array($courseItem['item_id'], $completedItemIds, true)
            )->count();

            $pendingActionItems = $pendingActionItems->merge(
                $courseItems
                    ->filter(
                        fn (array $courseItem) => ! in_array($courseItem['item_id'], $completedItemIds, true)
                            && (
                                $courseItem['item_type'] === CourseSessionItem::TYPE_TASK
                                || ($courseItem['item_type'] === CourseSessionItem::TYPE_QUIZ && $courseItem['is_live'])
                            )
                    )
                    ->map(function (array $courseItem) use ($course) {
                        $isQuiz = $courseItem['item_type'] === CourseSessionItem::TYPE_QUIZ;

                        return [
                            'course_id' => (int) $course->id,
                            'course_title' => $course->title ?: 'Untitled Course',
                            'item_title' => $courseItem['title'],
                            'item_type' => $courseItem['item_type'],
                            'item_type_label' => $isQuiz ? 'Live Quiz' : 'Task',
                            'status_label' => $isQuiz ? 'Live Now' : 'Pending Submission',
                            'route' => $courseItem['route'],
                            'week_number' => $courseItem['week_number'],
                            'session_number' => $courseItem['session_number'],
                            'session_title' => $courseItem['session_title'],
                        ];
                    })
            );

            $learningItems->push([
                'course_id' => (int) $course->id,
                'title' => $course->title ?: 'Untitled Course',
                'category' => $course->category?->name ?? 'General',
                'provider' => 'LMS Academy',
                'thumbnail_url' => $course->thumbnail_url,
                'progress_percent' => $progressPercent,
                'hours_done' => $hoursDone,
                'hours_total' => $hoursTotal,
                'accent' => $palette[$index % count($palette)],
                'resume_route' => $nextPendingItem['route'] ?? route('student.courses.show', $course),
                'resume_item_title' => $nextPendingItem['title'] ?? 'Open course workspace',
                'resume_item_type' => $nextPendingItem
                    ? ucwords(str_replace('_', ' ', (string) $nextPendingItem['item_type']))
                    : 'Course',
                'pending_tasks_count' => $pendingTaskCount,
                'live_quizzes_count' => $liveQuizCount,
                'has_pending_resume' => $nextPendingItem !== null,
            ]);

            if ($progressPercent >= 100) {
                $certificate = $certificateService->buildCompletedCertificate($enrollment);

                if ($certificate) {
                    $certificates->push($certificate);
                }
            }
        }

        $allSubmissions = CourseItemSubmission::query()
            ->with(['item.session.week.course.category', 'quizAnswers.question', 'reviewer'])
            ->whereHas('enrollment', fn ($query) => $query->where('student_id', $user->id))
            ->latest('submitted_at')
            ->latest('id')
            ->get();

        $latestSubmissions = $allSubmissions
            ->groupBy(fn (CourseItemSubmission $submission): string => $submission->course_enrollment_id.'-'.$submission->course_session_item_id)
            ->map->first()
            ->values();

        foreach ($allSubmissions as $submission) {
            $submittedAt = $submission->submitted_at;

            if (! $submittedAt) {
                continue;
            }

            $dateKey = $submittedAt->toDateString();
            $quizScore = $submission->submission_type === CourseSessionItem::TYPE_QUIZ
                ? $this->resolveQuizAnalyticsScore($submission)
                : 0;
            $activityByDate[$dateKey] = [
                'activity_count' => (int) (($activityByDate[$dateKey]['activity_count'] ?? 0) + 1),
                'estimated_hours' => round((float) ($activityByDate[$dateKey]['estimated_hours'] ?? 0.0), 2),
                'items_completed' => (int) ($activityByDate[$dateKey]['items_completed'] ?? 0),
                'quiz_score_total' => (int) (($activityByDate[$dateKey]['quiz_score_total'] ?? 0) + $quizScore),
                'quiz_score_count' => (int) (($activityByDate[$dateKey]['quiz_score_count'] ?? 0) + ($submission->submission_type === CourseSessionItem::TYPE_QUIZ ? 1 : 0)),
            ];

            $item = $submission->item;
            $session = $item?->session;
            $week = $session?->week;
            $course = $week?->course;
            $isQuiz = $submission->submission_type === CourseSessionItem::TYPE_QUIZ;
            $statusLabel = match ($submission->review_status) {
                CourseItemSubmission::STATUS_REVIEWED => 'Completed',
                CourseItemSubmission::STATUS_REVISION_REQUESTED => 'Revision Needed',
                default => 'Pending Review',
            };
            $tone = match ($submission->review_status) {
                CourseItemSubmission::STATUS_REVIEWED => 'completed',
                CourseItemSubmission::STATUS_REVISION_REQUESTED => 'revision',
                default => 'pending',
            };

            $activityFeed->push([
                'timestamp' => (int) $submittedAt->timestamp,
                'status_label' => $statusLabel,
                'tone' => $tone,
                'title' => $item?->title ?? ($isQuiz ? 'Quiz submission' : 'Task submission'),
                'description' => ($isQuiz ? 'Quiz update in ' : 'Submission in ').($course?->title ?? 'Course'),
                'meta' => trim(implode(' | ', array_filter([
                    $submission->reviewStatusLabel(),
                    $item ? ucwords(str_replace('_', ' ', (string) $submission->submission_type)) : null,
                ]))),
                'occurred_at_human' => $submittedAt->diffForHumans(),
                'route' => ($course && $week && $session && $item)
                    ? $this->buildStudentCourseItemRoute((int) $course->id, (int) $week->id, (int) $session->id, (int) $item->id)
                    : route('student.courses'),
                'route_label' => $tone === 'revision' ? 'Revise now' : 'Open lesson',
                'secondary_route' => $submission->file_path
                    ? route('course-item-submissions.download', $submission)
                    : null,
                'secondary_label' => $submission->file_path ? 'Download file' : null,
            ]);
        }

        $recentSubmissions = $allSubmissions
            ->take(5)
            ->map(function (CourseItemSubmission $submission): array {
                $item = $submission->item;
                $session = $item?->session;
                $week = $session?->week;
                $course = $week?->course;

                return [
                    'title' => $item?->title ?? 'Submission',
                    'course_title' => $course?->title ?? 'Course',
                    'submission_type' => strtoupper((string) $submission->submission_type),
                    'status_label' => $submission->reviewStatusLabel(),
                    'status_tone' => $submission->reviewStatusTone(),
                    'submitted_at_human' => optional($submission->submitted_at)->diffForHumans(),
                    'answer_text' => (string) $submission->answer_text,
                    'review_notes' => (string) $submission->review_notes,
                    'file_name' => $submission->file_name,
                    'download_route' => $submission->file_path
                        ? route('course-item-submissions.download', $submission)
                        : null,
                    'open_route' => ($course && $week && $session && $item)
                        ? $this->buildStudentCourseItemRoute((int) $course->id, (int) $week->id, (int) $session->id, (int) $item->id)
                        : route('student.courses'),
                ];
            });

        $latestQuizSubmissions = $latestSubmissions
            ->filter(fn (CourseItemSubmission $submission): bool => $submission->submission_type === CourseSessionItem::TYPE_QUIZ)
            ->values();

        $pendingSubmissionCount = $latestSubmissions
            ->filter(fn (CourseItemSubmission $submission): bool => in_array(
                $submission->review_status,
                [
                    CourseItemSubmission::STATUS_PENDING_REVIEW,
                    CourseItemSubmission::STATUS_REVISION_REQUESTED,
                ],
                true
            ))
            ->count();

        $sortedCertificates = $certificates
            ->sortByDesc('issued_at_timestamp')
            ->values();

        foreach ($sortedCertificates as $certificate) {
            $issuedAt = $certificate['issued_at'] ?? null;

            if (! ($issuedAt instanceof Carbon)) {
                continue;
            }

            $activityFeed->push([
                'timestamp' => (int) $issuedAt->timestamp,
                'status_label' => 'Certificate',
                'tone' => 'certificate',
                'title' => (string) ($certificate['course_title'] ?? 'Course certificate'),
                'description' => 'Certificate unlocked in '.($certificate['category'] ?? 'General'),
                'meta' => 'Issued '.$issuedAt->format('M d, Y'),
                'occurred_at_human' => $issuedAt->diffForHumans(),
                'route' => (string) ($certificate['download_pdf_route'] ?? route('student.certificates')),
                'route_label' => 'Download PDF',
                'secondary_route' => $certificate['download_svg_route'] ?? null,
                'secondary_label' => ! empty($certificate['download_svg_route']) ? 'SVG' : null,
            ]);
        }

        $resumeCourse = $learningItems->first(fn (array $item) => (bool) $item['has_pending_resume'])
            ?: $learningItems->first();

        $resumeItem = $resumeCourse
            ? [
                'course_id' => $resumeCourse['course_id'],
                'course_title' => $resumeCourse['title'],
                'route' => $resumeCourse['resume_route'],
                'item_title' => $resumeCourse['resume_item_title'],
                'item_type' => $resumeCourse['resume_item_type'],
                'progress_percent' => $resumeCourse['progress_percent'],
                'hours_done' => $resumeCourse['hours_done'],
                'hours_total' => $resumeCourse['hours_total'],
                'pending_tasks_count' => $resumeCourse['pending_tasks_count'],
                'live_quizzes_count' => $resumeCourse['live_quizzes_count'],
            ]
            : null;

        $pendingActionItems = $pendingActionItems
            ->sortByDesc(fn (array $item) => $item['item_type'] === CourseSessionItem::TYPE_QUIZ)
            ->values();

        return [
            'learningItems' => $learningItems->values(),
            'resumeItem' => $resumeItem,
            'pendingActionItems' => $pendingActionItems->take(6)->values(),
            'pendingActionSummary' => [
                'tasks' => $pendingActionItems->where('item_type', CourseSessionItem::TYPE_TASK)->count(),
                'live_quizzes' => $pendingActionItems->where('item_type', CourseSessionItem::TYPE_QUIZ)->count(),
                'total' => $pendingActionItems->count(),
            ],
            'recentSubmissions' => $recentSubmissions,
            'certificates' => $sortedCertificates->take(4)->values(),
            'analytics' => $this->buildStudentAnalytics(
                learningItems: $learningItems->values(),
                activityByDate: $activityByDate,
                latestQuizSubmissions: $latestQuizSubmissions,
                overallCompletedItems: $overallCompletedItems,
                overallTotalItems: $overallTotalItems,
                certificates: $sortedCertificates,
                pendingSubmissionCount: $pendingSubmissionCount,
                activityFeed: $activityFeed->sortByDesc('timestamp')->take(12)->values()
            ),
        ];
    }

    private function buildStudentCourseItemRoute(int $courseId, int $weekId, int $sessionId, int $itemId): string
    {
        return route('student.courses.show', [
            'course' => $courseId,
            'week' => $weekId,
            'session' => $sessionId,
            'item' => $itemId,
        ]).'#learning-workspace';
    }

    /**
     * @param  \Illuminate\Support\Collection<int, array<string, mixed>>  $learningItems
     * @param  array<string, array{activity_count:int, estimated_hours:float, items_completed:int, quiz_score_total:int, quiz_score_count:int}>  $activityByDate
     * @param  \Illuminate\Support\Collection<int, \App\Models\CourseItemSubmission>  $latestQuizSubmissions
     * @return array<string, mixed>
     */
    private function buildStudentAnalytics(
        $learningItems,
        array $activityByDate,
        $latestQuizSubmissions,
        int $overallCompletedItems,
        int $overallTotalItems,
        $certificates,
        int $pendingSubmissionCount,
        $activityFeed
    ): array {
        $analytics = $this->emptyStudentAnalytics();
        $today = now()->startOfDay();
        $overallCompletionPercent = $overallTotalItems > 0
            ? (int) round(($overallCompletedItems / $overallTotalItems) * 100)
            : 0;

        $courseCount = $learningItems->count();
        $completedCourses = $learningItems->where('progress_percent', '>=', 100)->count();
        $inProgressCourses = $learningItems->filter(
            fn (array $item): bool => (int) ($item['progress_percent'] ?? 0) > 0
                && (int) ($item['progress_percent'] ?? 0) < 100
        )->count();
        $notStartedCourses = max(0, $courseCount - $completedCourses - $inProgressCourses);

        $completionSeries = $learningItems
            ->sortByDesc('progress_percent')
            ->take(6)
            ->values()
            ->map(fn (array $item): array => [
                'course_id' => (int) ($item['course_id'] ?? 0),
                'title' => (string) ($item['title'] ?? 'Course'),
                'category' => (string) ($item['category'] ?? 'General'),
                'percent' => (int) ($item['progress_percent'] ?? 0),
                'hours_done' => (float) ($item['hours_done'] ?? 0),
                'hours_total' => (float) ($item['hours_total'] ?? 0),
                'route' => (string) ($item['resume_route'] ?? route('student.courses')),
            ]);

        $completionSegments = collect([
            [
                'key' => 'completed',
                'label' => 'Completed',
                'value' => $completedCourses,
                'color' => '#1f8d77',
            ],
            [
                'key' => 'in_progress',
                'label' => 'In Progress',
                'value' => $inProgressCourses,
                'color' => '#1f6fd3',
            ],
            [
                'key' => 'not_started',
                'label' => 'Not Started',
                'value' => $notStartedCourses,
                'color' => '#d7e3f4',
            ],
        ])->map(function (array $segment) use ($courseCount): array {
            $segment['percent'] = $courseCount > 0
                ? round(($segment['value'] / $courseCount) * 100, 2)
                : 0.0;

            return $segment;
        })->values();

        $weeklyDays = collect();
        $weeklyWindowStart = $today->copy()->subDays(6);

        for ($offset = 0; $offset < 7; $offset++) {
            $day = $weeklyWindowStart->copy()->addDays($offset);
            $dateKey = $day->toDateString();
            $quizScoreCount = (int) ($activityByDate[$dateKey]['quiz_score_count'] ?? 0);

            $weeklyDays->push([
                'date' => $dateKey,
                'label' => $day->format('D'),
                'full_label' => $day->format('M j'),
                'hours' => round((float) ($activityByDate[$dateKey]['estimated_hours'] ?? 0), 1),
                'items' => (int) ($activityByDate[$dateKey]['items_completed'] ?? 0),
                'quiz_score' => $quizScoreCount > 0
                    ? (int) round(((int) ($activityByDate[$dateKey]['quiz_score_total'] ?? 0)) / $quizScoreCount)
                    : 0,
                'quiz_count' => $quizScoreCount,
                'activity_count' => (int) ($activityByDate[$dateKey]['activity_count'] ?? 0),
                'is_today' => $dateKey === $today->toDateString(),
            ]);
        }

        $weeklyViews = [
            'hours' => [
                'label' => 'Hours spent',
                'summary_label' => 'This week',
                'summary_value' => round((float) $weeklyDays->sum('hours'), 1),
                'summary_suffix' => 'h',
                'summary_decimals' => 1,
                'secondary_label' => 'Daily average',
                'secondary_value' => round((float) ($weeklyDays->avg('hours') ?? 0), 1),
                'secondary_suffix' => 'h',
                'secondary_decimals' => 1,
                'max' => max(1.0, (float) $weeklyDays->max('hours')),
                'series' => $weeklyDays->map(fn (array $day): array => [
                    'label' => $day['label'],
                    'full_label' => $day['full_label'],
                    'value' => (float) $day['hours'],
                    'tooltip' => number_format((float) $day['hours'], 1).'h',
                    'is_today' => $day['is_today'],
                ]),
            ],
            'items' => [
                'label' => 'Items completed',
                'summary_label' => 'This week',
                'summary_value' => (int) $weeklyDays->sum('items'),
                'summary_suffix' => '',
                'summary_decimals' => 0,
                'secondary_label' => 'Active days',
                'secondary_value' => (int) $weeklyDays->filter(fn (array $day): bool => (int) $day['items'] > 0)->count(),
                'secondary_suffix' => '',
                'secondary_decimals' => 0,
                'max' => max(1, (int) $weeklyDays->max('items')),
                'series' => $weeklyDays->map(fn (array $day): array => [
                    'label' => $day['label'],
                    'full_label' => $day['full_label'],
                    'value' => (int) $day['items'],
                    'tooltip' => (int) $day['items'].' item(s)',
                    'is_today' => $day['is_today'],
                ]),
            ],
            'quiz_scores' => [
                'label' => 'Quiz scores',
                'summary_label' => 'Average',
                'summary_value' => (int) round((float) ($weeklyDays->filter(fn (array $day): bool => (int) $day['quiz_count'] > 0)->avg('quiz_score') ?? 0)),
                'summary_suffix' => '%',
                'summary_decimals' => 0,
                'secondary_label' => 'Quiz days',
                'secondary_value' => (int) $weeklyDays->filter(fn (array $day): bool => (int) $day['quiz_count'] > 0)->count(),
                'secondary_suffix' => '',
                'secondary_decimals' => 0,
                'max' => 100,
                'series' => $weeklyDays->map(fn (array $day): array => [
                    'label' => $day['label'],
                    'full_label' => $day['full_label'],
                    'value' => (int) $day['quiz_score'],
                    'tooltip' => (int) $day['quiz_score'].'%',
                    'is_today' => $day['is_today'],
                ]),
            ],
        ];

        $quizSeriesSource = $latestQuizSubmissions
            ->sortBy(fn (CourseItemSubmission $submission): int => (int) (optional($submission->submitted_at)->timestamp ?? 0))
            ->values();

        if ($quizSeriesSource->count() > 6) {
            $quizSeriesSource = $quizSeriesSource->slice(-6)->values();
        }

        $quizSeries = $quizSeriesSource->values()->map(function (CourseItemSubmission $submission, int $index): array {
            $item = $submission->item;
            $session = $item?->session;
            $week = $session?->week;
            $course = $week?->course;
            $score = $this->resolveQuizAnalyticsScore($submission);

            return [
                'label' => 'Q'.($index + 1),
                'score' => $score,
                'status' => $submission->reviewStatusLabel(),
                'course_title' => $course?->title ?? 'Course',
                'item_title' => $item?->title ?? 'Quiz',
            ];
        });

        $courseQuizSeries = $latestQuizSubmissions
            ->groupBy(function (CourseItemSubmission $submission): string {
                $course = $submission->item?->session?->week?->course;

                return (string) ($course?->id ?? 'enrollment-'.$submission->course_enrollment_id);
            })
            ->map(function ($submissions): array {
                $course = $submissions->first()?->item?->session?->week?->course;
                $scores = $submissions->map(
                    fn (CourseItemSubmission $submission): int => $this->resolveQuizAnalyticsScore($submission)
                );

                return [
                    'title' => $course?->title ?? 'Course',
                    'category' => $course?->category?->name ?? 'General',
                    'score' => (int) round((float) ($scores->avg() ?? 0)),
                    'attempts' => $submissions->count(),
                    'route' => $course ? route('student.courses.show', $course) : route('student.courses'),
                ];
            })
            ->sortByDesc(fn (array $item): int => ((int) $item['attempts'] * 1000) + (int) $item['score'])
            ->take(6)
            ->values();

        $categoryRadar = $learningItems
            ->groupBy('category')
            ->map(fn ($items, $category): array => [
                'label' => (string) $category,
                'value' => (int) round((float) $items->avg('progress_percent')),
                'target' => 100,
                'courses' => $items->count(),
            ])
            ->sortByDesc('courses')
            ->take(6)
            ->values();

        $activeDateKeys = collect($activityByDate)
            ->filter(fn (array $metrics): bool => (int) ($metrics['activity_count'] ?? 0) > 0)
            ->keys()
            ->sort()
            ->values();

        $currentStreak = 0;
        $streakCursor = $today->copy();
        while ((int) ($activityByDate[$streakCursor->toDateString()]['activity_count'] ?? 0) > 0) {
            $currentStreak++;
            $streakCursor->subDay();
        }

        $longestStreak = 0;
        $runningStreak = 0;
        $previousActiveDate = null;

        foreach ($activeDateKeys as $dateKey) {
            $activeDate = Carbon::parse($dateKey)->startOfDay();

            if ($previousActiveDate && $previousActiveDate->diffInDays($activeDate) === 1) {
                $runningStreak++;
            } else {
                $runningStreak = 1;
            }

            $longestStreak = max($longestStreak, $runningStreak);
            $previousActiveDate = $activeDate;
        }

        $tracker = collect();
        $trackerStart = $today->copy()->subDays(13);

        for ($offset = 0; $offset < 14; $offset++) {
            $day = $trackerStart->copy()->addDays($offset);
            $dateKey = $day->toDateString();
            $activityCount = (int) ($activityByDate[$dateKey]['activity_count'] ?? 0);

            $tracker->push([
                'date' => $dateKey,
                'day' => (int) $day->format('j'),
                'weekday' => $day->format('D'),
                'activity_count' => $activityCount,
                'is_active' => $activityCount > 0,
                'is_today' => $dateKey === $today->toDateString(),
            ]);
        }

        $heatmapDisplayStart = $today->copy()->subMonths(6)->startOfDay();
        $heatmapCursor = $heatmapDisplayStart->copy()->startOfWeek(Carbon::MONDAY);
        $heatmapEnd = $today->copy()->endOfWeek(Carbon::SUNDAY);
        $heatmapWeeks = collect();

        while ($heatmapCursor->lessThanOrEqualTo($heatmapEnd)) {
            $weekStart = $heatmapCursor->copy();
            $monthLabel = null;
            $weekDays = collect();

            for ($dayOffset = 0; $dayOffset < 7; $dayOffset++) {
                $day = $weekStart->copy()->addDays($dayOffset);
                $dateKey = $day->toDateString();
                $activityCount = (int) ($activityByDate[$dateKey]['activity_count'] ?? 0);
                $isInRange = $day->greaterThanOrEqualTo($heatmapDisplayStart)
                    && $day->lessThanOrEqualTo($today);

                if ($monthLabel === null && $isInRange && ($day->day <= 7 || $heatmapWeeks->isEmpty())) {
                    $monthLabel = $day->format('M');
                }

                $weekDays->push([
                    'date' => $dateKey,
                    'weekday' => $day->format('D'),
                    'day' => (int) $day->format('j'),
                    'activity_count' => $activityCount,
                    'hours' => round((float) ($activityByDate[$dateKey]['estimated_hours'] ?? 0), 1),
                    'level' => match (true) {
                        $activityCount >= 6 => 4,
                        $activityCount >= 4 => 3,
                        $activityCount >= 2 => 2,
                        $activityCount === 1 => 1,
                        default => 0,
                    },
                    'is_in_range' => $isInRange,
                    'is_today' => $dateKey === $today->toDateString(),
                ]);
            }

            $heatmapWeeks->push([
                'month_label' => $monthLabel,
                'days' => $weekDays,
            ]);

            $heatmapCursor->addWeek();
        }

        $analytics['stats'] = [
            [
                'label' => 'Courses',
                'value' => $courseCount,
                'suffix' => '',
                'decimals' => 0,
                'caption' => 'enrolled right now',
                'tone' => 'blue',
            ],
            [
                'label' => 'Completion',
                'value' => $overallCompletionPercent,
                'suffix' => '%',
                'decimals' => 0,
                'caption' => $overallCompletedItems.' of '.$overallTotalItems.' items completed',
                'tone' => 'indigo',
            ],
            [
                'label' => 'Certificates',
                'value' => $certificates->count(),
                'suffix' => '',
                'decimals' => 0,
                'caption' => 'earned so far',
                'tone' => 'green',
            ],
            [
                'label' => 'Pending Submissions',
                'value' => $pendingSubmissionCount,
                'suffix' => '',
                'decimals' => 0,
                'caption' => 'awaiting review or update',
                'tone' => 'amber',
            ],
        ];

        $analytics['weekly'] = [
            'default_view' => 'hours',
            'views' => $weeklyViews,
        ];

        $analytics['completion'] = [
            'overall_percent' => $overallCompletionPercent,
            'completed_courses' => $completedCourses,
            'in_progress_courses' => $inProgressCourses,
            'not_started_courses' => $notStartedCourses,
            'course_count' => $courseCount,
            'completed_items' => $overallCompletedItems,
            'total_items' => $overallTotalItems,
            'segments' => $completionSegments,
            'series' => $completionSeries,
        ];

        $analytics['radar'] = [
            'series' => $categoryRadar,
            'goal_label' => 'Target = 100% completion across active categories',
        ];

        $analytics['heatmap'] = [
            'weeks' => $heatmapWeeks,
            'active_days' => $activeDateKeys->count(),
            'start_label' => $heatmapDisplayStart->format('M j'),
            'end_label' => $today->format('M j'),
        ];

        $analytics['quiz'] = [
            'average_score' => (int) round((float) ($quizSeries->avg('score') ?? 0)),
            'attempted' => $latestQuizSubmissions->count(),
            'reviewed' => $latestQuizSubmissions->where('review_status', CourseItemSubmission::STATUS_REVIEWED)->count(),
            'pending' => $latestQuizSubmissions->where('review_status', CourseItemSubmission::STATUS_PENDING_REVIEW)->count(),
            'revision_requested' => $latestQuizSubmissions->where('review_status', CourseItemSubmission::STATUS_REVISION_REQUESTED)->count(),
            'series' => $quizSeries,
            'course_series' => $courseQuizSeries,
            'derived_from_reviews' => $latestQuizSubmissions->contains(
                fn (CourseItemSubmission $submission): bool => $submission->submission_type === CourseSessionItem::TYPE_QUIZ
                    && $submission->score_percent === null
            ),
        ];

        $analytics['streak'] = [
            'current' => $currentStreak,
            'longest' => $longestStreak,
            'active_days' => $activeDateKeys->count(),
            'tracker' => $tracker,
            'tracker_active' => $tracker->where('is_active', true)->count(),
        ];

        $analytics['activity_feed'] = $activityFeed->values();

        return $analytics;
    }

    private function resolveQuizAnalyticsScore(CourseItemSubmission $submission): int
    {
        if ($submission->score_percent !== null) {
            return max(0, min(100, (int) $submission->score_percent));
        }

        return match ($submission->review_status) {
            CourseItemSubmission::STATUS_REVIEWED => 100,
            CourseItemSubmission::STATUS_PENDING_REVIEW => 65,
            CourseItemSubmission::STATUS_REVISION_REQUESTED => 30,
            default => 0,
        };
    }

    /**
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    private function resolveLearningItems(User $user)
    {
        $palette = ['blue', 'green', 'violet', 'orange', 'red', 'teal'];
        $fallbackProgress = [72, 45, 90, 18, 63, 35];
        $learningItems = collect();

        if ($user->role === User::ROLE_STUDENT) {
            $enrollments = CourseEnrollment::query()
                ->where('student_id', $user->id)
                ->with('course.category')
                ->latest('id')
                ->get();

            $enrollmentIds = $enrollments->pluck('id');
            $courseIds = $enrollments->pluck('course_id')->unique();

            $totalItemsByCourse = DB::table('course_session_items')
                ->join('course_sessions', 'course_sessions.id', '=', 'course_session_items.course_session_id')
                ->join('course_weeks', 'course_weeks.id', '=', 'course_sessions.course_week_id')
                ->whereIn('course_weeks.course_id', $courseIds)
                ->groupBy('course_weeks.course_id')
                ->select('course_weeks.course_id', DB::raw('count(course_session_items.id) as total_items'))
                ->pluck('total_items', 'course_weeks.course_id');

            $completedByEnrollment = CourseProgress::query()
                ->whereIn('course_enrollment_id', $enrollmentIds)
                ->whereNotNull('completed_at')
                ->groupBy('course_enrollment_id')
                ->select('course_enrollment_id', DB::raw('count(*) as completed_items'))
                ->pluck('completed_items', 'course_enrollment_id');

            $learningItems = $enrollments->map(function (CourseEnrollment $enrollment, int $index) use ($palette, $totalItemsByCourse, $completedByEnrollment): array {
                $course = $enrollment->course;
                $totalItems = max(1, (int) ($totalItemsByCourse[$enrollment->course_id] ?? 1));
                $completedItems = min($totalItems, (int) ($completedByEnrollment[$enrollment->id] ?? 0));
                $progressPercent = (int) round(($completedItems / $totalItems) * 100);
                $hoursTotal = max(1, (int) ($course?->duration_hours ?? 1));
                $hoursDone = round(($hoursTotal * $progressPercent) / 100, 1);

                return [
                    'course_id' => $course?->id,
                    'title' => $course?->title ?? 'Untitled Course',
                    'category' => $course?->category?->name ?? 'General',
                    'provider' => 'LMS Academy',
                    'thumbnail_url' => $course?->thumbnail_url,
                    'progress_percent' => $progressPercent,
                    'hours_done' => $hoursDone,
                    'hours_total' => $hoursTotal,
                    'accent' => $palette[$index % count($palette)],
                ];
            });
        }

        if ($learningItems->isNotEmpty()) {
            return $learningItems->values();
        }

        return Course::query()
            ->with('category')
            ->latest('id')
            ->take(4)
            ->get()
            ->map(function (Course $course, int $index) use ($palette, $fallbackProgress): array {
                $progressPercent = $fallbackProgress[$index % count($fallbackProgress)];
                $hoursTotal = max(1, (int) $course->duration_hours);

                return [
                    'course_id' => $course->id,
                    'title' => $course->title,
                    'category' => $course->category?->name ?? 'General',
                    'provider' => 'LMS Academy',
                    'thumbnail_url' => $course->thumbnail_url,
                    'progress_percent' => $progressPercent,
                    'hours_done' => round(($hoursTotal * $progressPercent) / 100, 1),
                    'hours_total' => $hoursTotal,
                    'accent' => $palette[$index % count($palette)],
                ];
            })
            ->values();
    }
}
