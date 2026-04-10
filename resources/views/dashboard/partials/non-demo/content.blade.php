            @if ($isStudent)
                <div class="student-dashboard-columns">
                    <div class="student-dashboard-main">
                        <article class="resume-panel">
                            @if (!empty($studentResumeItem))
                                <div class="resume-panel-head">
                                    <div class="resume-copy">
                                        <span class="mini-cta">Resume Last Lesson</span>
                                        <h2>{{ $studentResumeItem['item_title'] }}</h2>
                                        <p class="resume-note">
                                            {{ $studentResumeItem['item_type'] }} in {{ $studentResumeItem['course_title'] }}.
                                            Jump back in exactly where you stopped.
                                        </p>
                                    </div>
                                    <a class="hero-btn" href="{{ $studentResumeItem['route'] }}">Resume Now</a>
                                </div>

                                <div class="resume-route-meta">
                                    <span class="pill">{{ $studentResumeItem['course_title'] }}</span>
                                    <span class="pill">{{ $studentResumeItem['item_type'] }}</span>
                                    @if (($studentResumeItem['pending_tasks_count'] ?? 0) > 0)
                                        <span class="focus-pill focus-pill--task">{{ $studentResumeItem['pending_tasks_count'] }} task{{ $studentResumeItem['pending_tasks_count'] === 1 ? '' : 's' }} pending</span>
                                    @endif
                                    @if (($studentResumeItem['live_quizzes_count'] ?? 0) > 0)
                                        <span class="focus-pill focus-pill--quiz">{{ $studentResumeItem['live_quizzes_count'] }} live quiz{{ $studentResumeItem['live_quizzes_count'] === 1 ? '' : 'zes' }}</span>
                                    @endif
                                </div>

                                <div class="resume-stat-grid">
                                    <div class="resume-stat">
                                        <span>Course Progress</span>
                                        <strong>{{ $studentResumeItem['progress_percent'] ?? 0 }}%</strong>
                                    </div>
                                    <div class="resume-stat">
                                        <span>Hours Done</span>
                                        <strong>{{ $studentResumeItem['hours_done'] ?? 0 }}h</strong>
                                    </div>
                                    <div class="resume-stat">
                                        <span>Hours Total</span>
                                        <strong>{{ $studentResumeItem['hours_total'] ?? 0 }}h</strong>
                                    </div>
                                </div>
                            @else
                                <div class="resume-copy">
                                    <span class="mini-cta">Resume Last Lesson</span>
                                    <h2>Your learning space is ready</h2>
                                    <p class="resume-note">Once you enroll in courses and open lessons, your exact resume link will appear here automatically.</p>
                                    <div>
                                        <a class="hero-btn" href="{{ route('student.courses') }}">Open My Courses</a>
                                    </div>
                                </div>
                            @endif
                        </article>

                        @include('dashboard.partials.student.progress-analytics')

                        <div class="student-column-group">
                            <span class="student-column-label">Learning Hub</span>
            @endif

            <section @class(['dashboard-section' => $isStudent, 'student-learning-section' => $isStudent])>
                <div class="section-head">
                    <div>
                        <h2>{{ $learningTitle }}</h2>
                        <p>{{ $learningSubtitle }}</p>
                    </div>
                    <a class="section-link" href="{{ $allCoursesRoute }}">{{ $learningActionLabel }}</a>
                </div>
                <div class="learning-grid" @unless($isStudent) style="margin-top: 10px;" @endunless>
                    @forelse ($learningItems as $index => $item)
                        @php
                            $itemRoute = !empty($item['course_id'])
                                ? ($isStudent
                                    ? ($item['resume_route'] ?? route('student.courses.show', $item['course_id']))
                                    : route('courses.show', $item['course_id']))
                                : $allCoursesRoute;
                            $isAssigned = ! $isTrainer || in_array($item['course_id'] ?? 0, $assignedCourseIds ?? [], true);
                        @endphp
                        @if ($isTrainer && ! $isAssigned)
                            <article class="course-card disabled">
                        @else
                            <a href="{{ $itemRoute }}" class="course-card" style="text-decoration: none; color: inherit;">
                        @endif
                            @php
                                $thumb = $item['thumbnail_url'] ?? '';
                                $topStyle = $thumb
                                    ? "background-image: url('{$thumb}')"
                                    : '';
                                $topClass = $thumb ? '' : ($accentClass[$item['accent']] ?? 'accent-blue');
                            @endphp
                            <div class="course-top {{ $topClass }}" @if ($thumb) style="{{ $topStyle }}" @endif>
                                <div class="icon-box">{{ $courseIcons[$index % count($courseIcons)] }}</div>
                                <span class="badge">{{ min(100, (int) $item['progress_percent']) }}%</span>
                                @if ($isTrainer && ! $isAssigned)
                                    <span class="course-lock">Locked</span>
                                @endif
                            </div>
                            <div class="course-body">
                                <span class="pill">{{ $item['category'] }}</span>
                                <h3>{{ $item['title'] }}</h3>
                                <p class="course-meta">{{ $item['provider'] }}</p>
                                <div class="bar-track">
                                    <div class="bar-val {{ $accentClass[$item['accent']] ?? 'accent-blue' }}" style="width: {{ min(100, (int) $item['progress_percent']) }}%"></div>
                                </div>
                                <div class="course-foot">
                                    <span>{{ $item['hours_done'] }}h / {{ $item['hours_total'] }}h</span>
                                    <span>{{ $item['progress_percent'] }}%</span>
                                </div>
                                @if ($isStudent)
                                    <span class="mini-cta">Continue</span>
                                @endif
                            </div>
                        @if ($isTrainer && ! $isAssigned)
                            </article>
                        @else
                            </a>
                        @endif
                    @empty
                        <article class="course-card"><div class="course-body"><h3>No learning data yet</h3><p class="course-meta">No assigned or enrolled courses found.</p></div></article>
                    @endforelse
                </div>
            </section>

            @if ($isStudent)
                <section class="dashboard-section">
                    <div class="section-head">
                        <div>
                            <h2>Recent Activity Feed</h2>
                            <p>Latest learning activity across completed items, pending reviews, revision requests, and earned certificates.</p>
                        </div>
                        <a class="section-link" href="{{ route('student.history') }}">Open history -&gt;</a>
                    </div>

                    <div class="activity-feed-grid">
                        @forelse (collect($studentAnalytics['activity_feed'] ?? []) as $activity)
                            <article class="activity-feed-card activity-feed-card--{{ $activity['tone'] ?? 'completed' }}">
                                <div class="activity-feed-head">
                                    <div class="activity-feed-copy">
                                        <div class="activity-feed-topline">{{ $activity['occurred_at_human'] ?? 'Recently' }}</div>
                                        <strong>{{ $activity['title'] ?? 'Activity' }}</strong>
                                        <p>{{ $activity['description'] ?? '' }}</p>
                                    </div>
                                    <div class="activity-feed-badges">
                                        <span class="activity-feed-badge activity-feed-badge--{{ $activity['tone'] ?? 'completed' }}">
                                            {{ $activity['status_label'] ?? 'Activity' }}
                                        </span>
                                    </div>
                                </div>

                                @if (!empty($activity['meta']))
                                    <div class="activity-feed-meta">{{ $activity['meta'] }}</div>
                                @endif

                                <div class="submission-actions">
                                    @if (!empty($activity['route']))
                                        <a class="btn btn-soft" href="{{ $activity['route'] }}">{{ $activity['route_label'] ?? 'Open' }}</a>
                                    @endif
                                    @if (!empty($activity['secondary_route']))
                                        <a class="btn" href="{{ $activity['secondary_route'] }}">{{ $activity['secondary_label'] ?? 'Open' }}</a>
                                    @endif
                                </div>
                            </article>
                        @empty
                            <div class="submission-empty">No recent activity yet. Your completed lessons, submissions, and certificates will appear here automatically.</div>
                        @endforelse
                    </div>
                </section>

                <section class="dashboard-section">
                        <div class="section-head">
                            <div>
                                <h2>Certificates</h2>
                                <p>Download PDF or SVG certificates for the courses you have fully completed.</p>
                            </div>
                            <a class="section-link" href="{{ route('student.certificates') }}">View all -&gt;</a>
                        </div>

                    <div class="certificate-grid">
                        @forelse ($studentCertificates as $certificate)
                            <article class="certificate-card">
                                <div class="certificate-card-top">
                                    <span class="pill">{{ $certificate['category'] }}</span>
                                    <span class="certificate-code">{{ $certificate['certificate_code'] }}</span>
                                </div>
                                <div>
                                    <h4>{{ $certificate['course_title'] }}</h4>
                                    <p class="certificate-meta">
                                        Issued {{ $certificate['issued_at_human'] }}
                                        &middot; {{ $certificate['hours_total'] }}h
                                        &middot; Trainer: {{ $certificate['trainer_name'] }}
                                    </p>
                                </div>
                                <div class="submission-actions">
                                    <a class="btn btn-soft" href="{{ $certificate['course_route'] }}">Open Course</a>
                                    <a class="btn" href="{{ $certificate['download_pdf_route'] }}">PDF</a>
                                    <a class="btn btn-soft" href="{{ $certificate['download_svg_route'] }}">SVG</a>
                                </div>
                            </article>
                        @empty
                            <div class="submission-empty">Complete a course to unlock your first certificate downloads.</div>
                        @endforelse
                    </div>
                </section>

                        </div>

                        <div class="student-column-group">
                            <span class="student-column-label">Discover More</span>
                            <section class="dashboard-section">
                                <div class="section-head">
                                    <div>
                                        <h2>Recommended Courses</h2>
                                        <p>Available courses from your existing LMS catalog.</p>
                                    </div>
                                    <a class="section-link" href="{{ route('courses.index') }}">Browse all -&gt;</a>
                                </div>
                                <div class="dashboard-section-body">
                                    <div class="recommend-grid">
                                        @forelse ($recommendedCourses as $index => $course)
                                            @php $tone = array_keys($accentClass)[$index % count($accentClass)]; @endphp
                                            <article class="recommend-card">
                                                <div class="recommend-top {{ $accentClass[$tone] }}">
                                                    <div class="icon-box">{{ $courseIcons[$index % count($courseIcons)] }}</div>
                                                </div>
                                                <div class="recommend-body">
                                                    <span class="pill">{{ $course['category'] }}</span>
                                                    <h4>{{ $course['title'] }}</h4>
                                                    <p class="recommend-meta">By {{ $course['provider'] }}</p>
                                                    <div class="recommend-foot">
                                                        <span>{{ $course['hours'] }}h total</span>
                                                        <a class="mini-btn" href="{{ route('courses.show', $course['id']) }}">View Course</a>
                                                    </div>
                                                </div>
                                            </article>
                                        @empty
                                            <article class="recommend-card"><div class="recommend-body"><h4>No courses available</h4></div></article>
                                        @endforelse
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>

                    <aside class="student-dashboard-side">
                        <article class="action-queue">
                            <div class="action-queue-head">
                                <div>
                                    <h3>Pending Tasks &amp; Live Quizzes</h3>
                                    <p>Open the most important actions directly from your dashboard.</p>
                                </div>
                                <a class="section-link" href="{{ route('student.courses') }}">Open courses -&gt;</a>
                            </div>

                            <div class="queue-summary">
                                <div class="queue-summary-box">
                                    <span>Pending Tasks</span>
                                    <strong>{{ $studentPendingActionSummary['tasks'] ?? 0 }}</strong>
                                </div>
                                <div class="queue-summary-box">
                                    <span>Live Quizzes</span>
                                    <strong>{{ $studentPendingActionSummary['live_quizzes'] ?? 0 }}</strong>
                                </div>
                                <div class="queue-summary-box">
                                    <span>Total Actions</span>
                                    <strong>{{ $studentPendingActionSummary['total'] ?? 0 }}</strong>
                                </div>
                            </div>

                            <div class="queue-list">
                                @forelse ($studentPendingActionItems as $actionItem)
                                    <a href="{{ $actionItem['route'] }}" class="queue-item">
                                        <div class="queue-item-top">
                                            <strong>{{ $actionItem['item_title'] }}</strong>
                                            <span class="queue-tag {{ $actionItem['item_type'] === \App\Models\CourseSessionItem::TYPE_QUIZ ? 'queue-tag--quiz' : 'queue-tag--task' }}">
                                                {{ $actionItem['item_type_label'] }}
                                            </span>
                                        </div>
                                        <p>{{ $actionItem['course_title'] }}</p>
                                        <div class="queue-meta">
                                            Week {{ $actionItem['week_number'] ?: '-' }} / Session {{ $actionItem['session_number'] ?: '-' }}
                                            &middot; {{ $actionItem['status_label'] }}
                                        </div>
                                    </a>
                                @empty
                                    <div class="submission-empty">No pending task or live quiz right now. You are nicely caught up.</div>
                                @endforelse
                            </div>
                        </article>

                        <div class="student-column-group">
                            <span class="student-column-label">Action Center</span>
                            <section class="dashboard-section dashboard-section--side quick-actions-card">
                                <div>
                                    <h2>Quick Actions</h2>
                                    <p>Open the most-used student shortcuts from one place.</p>
                                </div>
                                <div class="quick-actions-grid">
                                    @foreach ($quickActions as $action)
                                        <a class="quick-action-link" href="{{ $action['route'] }}">{{ $action['label'] }}</a>
                                    @endforeach
                                </div>
                            </section>

                            @if ($notifications->isNotEmpty())
                                <section class="dashboard-section dashboard-section--side student-side-card">
                                    <h3>Notifications</h3>
                                    @include('dashboard-notification-feed-v2', ['notifications' => $notifications])
                                </section>
                            @endif

                            <article class="dashboard-section dashboard-section--side student-side-card">
                                <h3>Skill Progress</h3>
                                @forelse ($skillProgress as $index => $skill)
                                    <div class="skill-row">
                                        <div class="skill-label">
                                            <span>{{ $skill['skill'] }}</span>
                                            <span>{{ $skill['progress'] }}%</span>
                                        </div>
                                        <div class="bar-track">
                                            <div class="bar-val {{ $accentClass[array_keys($accentClass)[$index % count($accentClass)]] }}" style="width: {{ $skill['progress'] }}%"></div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="muted" style="margin: 0;">No skill progress available.</p>
                                @endforelse
                            </article>

                            <article class="dashboard-section dashboard-section--side student-side-card">
                                <h3>Browse by Topic</h3>
                                <div class="topic-grid">
                                    @forelse ($topics as $topic)
                                        <a href="{{ route('courses.index') }}" class="topic" style="text-decoration: none; color: inherit;">
                                            <div class="topic-bullet">{{ strtoupper(substr($topic['name'], 0, 2)) }}</div>
                                            <div>
                                                <strong>{{ $topic['name'] }}</strong>
                                                <p>{{ number_format($topic['count']) }} courses</p>
                                            </div>
                                        </a>
                                    @empty
                                        <p class="muted" style="margin: 0;">No topics found.</p>
                                    @endforelse
                                </div>
                            </article>
                        </div>
                    </aside>
                </div>
            @else
                <section class="card">
                    <h2 style="margin: 0 0 10px;">Quick Actions</h2>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        @foreach ($quickActions as $action)
                            <a class="btn btn-soft" href="{{ $action['route'] }}">{{ $action['label'] }}</a>
                        @endforeach
                    </div>
                </section>

                @if ($notifications->isNotEmpty())
                    <section class="card">
                        <div class="page-head">
                            <div>
                                <h2>Notifications</h2>
                                <p>Latest LMS updates for your account.</p>
                            </div>
                        </div>
                        @include('dashboard-notification-feed-v2', ['notifications' => $notifications])
                    </section>
                @endif

                @if (in_array($user->role, [\App\Models\User::ROLE_SUPERADMIN, \App\Models\User::ROLE_ADMIN], true))
                    <section class="card">
                        <div class="section-head">
                            <div>
                                <h2>Recent Demo Task Submissions</h2>
                                <p>Latest submitted demo work with the student details entered at submission time.</p>
                            </div>
                            <a class="section-link" href="{{ route('demo-tasks.submissions-page') }}">Open all -&gt;</a>
                        </div>
                        <div class="admin-demo-submission-grid">
                            @forelse ($adminDemoSubmissions as $demoSubmission)
                                @php
                                    $demoLearner = $demoSubmission->assignment?->user;
                                    $videoRating = (int) ($demoSubmission->video_rating ?? 0);
                                @endphp
                                <article class="admin-demo-submission-card">
                                    <div style="display: grid; gap: 6px;">
                                        <strong>{{ $demoSubmission->assignment?->demoTask?->title ?? 'Demo Task' }}</strong>
                                        <div class="admin-demo-submission-meta">
                                            <span>{{ $demoSubmission->participant_name ?: 'Student details pending' }}</span>
                                            @if ($demoSubmission->participant_email)
                                                <span>{{ $demoSubmission->participant_email }}</span>
                                            @endif
                                            <span>{{ $demoSubmission->submitted_at?->diffForHumans() ?? 'Recently submitted' }}</span>
                                        </div>
                                    </div>
                                    <div class="admin-demo-user-grid">
                                        <div class="admin-demo-user-stat">
                                            <span>Phone</span>
                                            <strong>{{ $demoSubmission->participant_phone ?: '-' }}</strong>
                                        </div>
                                        <div class="admin-demo-user-stat">
                                            <span>Shared Login</span>
                                            <strong>{{ $demoLearner?->name ?? 'Demo User' }}</strong>
                                        </div>
                                        <div class="admin-demo-user-stat">
                                            <span>Assigned By</span>
                                            <strong>{{ $demoSubmission->assignment?->assigner?->name ?? 'Admin' }}</strong>
                                        </div>
                                        <div class="admin-demo-user-stat">
                                            <span>File</span>
                                            <strong>{{ $demoSubmission->file_name ?: 'No uploaded file' }}</strong>
                                        </div>
                                        <div class="admin-demo-user-stat">
                                            <span>Video Rating</span>
                                            @if ($videoRating > 0)
                                                <strong class="admin-demo-rating">
                                                    <span class="stars">{{ str_repeat('★', $videoRating) }}</span>
                                                    <span class="score">{{ $videoRating }}/5</span>
                                                </strong>
                                            @else
                                                <strong>-</strong>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($demoSubmission->answer_text)
                                        <div class="admin-demo-answer">{{ \Illuminate\Support\Str::limit($demoSubmission->answer_text, 180) }}</div>
                                    @endif
                                    <div class="demo-task-actions">
                                        @if ($demoLearner)
                                            <a class="btn btn-soft" href="{{ route('demo-tasks.submissions-page', ['user_id' => $demoLearner->id]) }}">Shared Login History</a>
                                        @endif
                                        @if ($demoSubmission->file_path)
                                            <a class="btn btn-soft" href="{{ route('demo-tasks.submissions.download', $demoSubmission) }}">Download File</a>
                                        @endif
                                    </div>
                                </article>
                            @empty
                                <div class="demo-empty">No demo task submissions yet. Submitted demo work will appear here for admin review.</div>
                            @endforelse
                        </div>
                    </section>
                @endif

                <section class="split-grid">
                    <article class="panel-box">
                        <h3>Skill Progress</h3>
                        @forelse ($skillProgress as $index => $skill)
                            <div class="skill-row">
                                <div class="skill-label">
                                    <span>{{ $skill['skill'] }}</span>
                                    <span>{{ $skill['progress'] }}%</span>
                                </div>
                                <div class="bar-track">
                                    <div class="bar-val {{ $accentClass[array_keys($accentClass)[$index % count($accentClass)]] }}" style="width: {{ $skill['progress'] }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="muted" style="margin: 0;">No skill progress available.</p>
                        @endforelse
                    </article>

                    <article class="panel-box">
                        <h3>Browse by Topic</h3>
                        <div class="topic-grid">
                            @forelse ($topics as $topic)
                                <a href="{{ route('courses.index') }}" class="topic" style="text-decoration: none; color: inherit;">
                                    <div class="topic-bullet">{{ strtoupper(substr($topic['name'], 0, 2)) }}</div>
                                    <div>
                                        <strong>{{ $topic['name'] }}</strong>
                                        <p>{{ number_format($topic['count']) }} courses</p>
                                    </div>
                                </a>
                            @empty
                                <p class="muted" style="margin: 0;">No topics found.</p>
                            @endforelse
                        </div>
                    </article>
                </section>

                <section>
                    <div class="section-head">
                        <div>
                            <h2>Recommended Courses</h2>
                            <p>Available courses from your existing LMS catalog.</p>
                        </div>
                        <a class="section-link" href="{{ route('courses.index') }}">Browse all -></a>
                    </div>
                    <div class="recommend-grid" style="margin-top: 10px;">
                        @forelse ($recommendedCourses as $index => $course)
                            @php $tone = array_keys($accentClass)[$index % count($accentClass)]; @endphp
                            <article class="recommend-card">
                                <div class="recommend-top {{ $accentClass[$tone] }}">
                                    <div class="icon-box">{{ $courseIcons[$index % count($courseIcons)] }}</div>
                                </div>
                                <div class="recommend-body">
                                    <span class="pill">{{ $course['category'] }}</span>
                                    <h4>{{ $course['title'] }}</h4>
                                    <p class="recommend-meta">By {{ $course['provider'] }}</p>
                                    <div class="recommend-foot">
                                        <span>{{ $course['hours'] }}h total</span>
                                        <a class="mini-btn" href="{{ route('courses.show', $course['id']) }}">View Course</a>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <article class="recommend-card"><div class="recommend-body"><h4>No courses available</h4></div></article>
                        @endforelse
                    </div>
                </section>
            @endif
