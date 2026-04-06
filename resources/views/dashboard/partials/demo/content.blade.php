            @php
                $demoVideoCount = $demoFeatureVideos->count();
                $pendingDemoAssignments = $demoAssignments->reject(fn ($row) => (bool) ($row['is_in_cooldown'] ?? false))->values();
                $coolingDownDemoAssignments = $demoAssignments->filter(fn ($row) => (bool) ($row['is_in_cooldown'] ?? false))->values();
                $nextDemoTaskReadyInSeconds = $coolingDownDemoAssignments
                    ->pluck('cooldown_remaining_seconds')
                    ->filter(fn ($seconds) => (int) $seconds > 0)
                    ->min();
                $demoSubmitPopup = session('demo_task_submission_popup');
            @endphp
            <section class="card demo-panel demo-panel--intro">
                <div class="page-head">
                    <div>
                        <h2>Welcome to your skill-building journey.</h2>
                        <p>Explore the demo experience, browse every feature video, and complete your tasks.</p>
                    </div>
                </div>
            </section>

            @if ($notifications->isNotEmpty())
                <section class="card demo-panel demo-panel--notify">
                    <div class="page-head">
                        <div>
                            <h2>Notifications</h2>
                            <p>Announcements and updates sent to your demo account appear here.</p>
                        </div>
                    </div>
                    @include('dashboard-notification-feed-v2', ['notifications' => $notifications])
                </section>
            @endif

            <section class="card demo-panel demo-panel--tasks">
                <div class="demo-section-title">
                    <div>
                        <h2>Demo Tasks</h2>
                        <p>This shared demo login can be used by multiple students. Each student must enter their own details and submit the assigned task from this panel.</p>
                    </div>
                </div>
                @if ($nextDemoTaskReadyInSeconds)
                    <div hidden data-demo-kiosk-reload-seconds="{{ $nextDemoTaskReadyInSeconds }}"></div>
                @endif
                <div class="demo-grid">
                    @forelse ($pendingDemoAssignments as $row)
                        @php
                            $task = $row['task'];
                            $assignment = $row['assignment'];
                            $hasTaskVideo = !empty($task?->task_video_path);
                            $taskVideoRatio = $task?->resolved_video_ratio ?? \App\Support\DemoVideoRatio::REEL;
                            $canDownloadResource = !empty($task?->resource_file_path);
                            $hasToolsLink = !empty($task?->ai_video_url);
                            $hasTaskActions = $canDownloadResource || $hasToolsLink;
                        @endphp
                        <div class="demo-task-card">
                            <div class="demo-task-top">
                                <strong>{{ $task?->title ?? 'Demo Task' }}</strong>
                                <div class="demo-task-meta">{{ $task?->description ?? 'Complete this task to see how submissions work.' }}</div>
                                @if ($hasTaskActions)
                                    <div class="demo-task-actions">
                                        @if ($canDownloadResource)
                                            <a class="btn btn-soft" href="{{ route('demo-tasks.download', $task) }}">Download Resource</a>
                                        @endif
                                        @if ($hasToolsLink)
                                            <a class="btn btn-soft" href="{{ $task->ai_video_url }}" target="_blank" rel="noopener">Tools</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="demo-task-shell {{ $hasTaskVideo ? 'demo-task-shell--'.$taskVideoRatio : 'no-video' }}">
                                @if ($hasTaskVideo)
                                    <div class="demo-task-media-col">
                                        <div class="demo-task-video demo-task-video--{{ $taskVideoRatio }}">
                                            <div class="demo-task-video-note">Watch this {{ strtolower($task?->video_ratio_label ?? 'reel') }} task video first, then complete the task below.</div>
                                            <div class="demo-task-video-frame demo-task-video-frame--{{ $taskVideoRatio }}">
                                                <video controls preload="metadata" controlslist="nodownload" playsinline>
                                                    <source src="{{ route('demo-tasks.video', $task) }}" type="{{ $task->task_video_mime ?: 'video/mp4' }}">
                                                </video>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="demo-task-form-col">
                                    @if ($assignment)
                                        <form method="POST" action="{{ route('demo-assignments.submit', $assignment) }}" enctype="multipart/form-data" class="demo-submit-panel">
                                            @csrf
                                            <div class="demo-submit-grid">
                                                <div class="demo-submit-field">
                                                    <label for="participant_name_{{ $assignment->id }}">Student Name</label>
                                                    <input
                                                        id="participant_name_{{ $assignment->id }}"
                                                        type="text"
                                                        name="participant_name"
                                                        value="{{ old('participant_name') }}"
                                                        maxlength="120"
                                                        placeholder="Enter full name"
                                                        required
                                                    >
                                                </div>
                                                <div class="demo-submit-field">
                                                    <label for="participant_email_{{ $assignment->id }}">Gmail / Email</label>
                                                    <input
                                                        id="participant_email_{{ $assignment->id }}"
                                                        type="email"
                                                        name="participant_email"
                                                        value="{{ old('participant_email') }}"
                                                        maxlength="190"
                                                        placeholder="Enter email address"
                                                        required
                                                    >
                                                </div>
                                                <div class="demo-submit-field">
                                                    <label for="participant_phone_{{ $assignment->id }}">Phone Number</label>
                                                    <input
                                                        id="participant_phone_{{ $assignment->id }}"
                                                        type="tel"
                                                        name="participant_phone"
                                                        value="{{ old('participant_phone') }}"
                                                        maxlength="40"
                                                        placeholder="Enter mobile number"
                                                        required
                                                    >
                                                </div>
                                            </div>
                                            @if ($hasTaskVideo)
                                                <div class="demo-submit-block demo-rating-block">
                                                    <h4>Rate Task Video</h4>
                                                    <div class="demo-rating-input" role="radiogroup" aria-label="Rate this task video from 1 to 5 stars">
                                                        @for ($star = 5; $star >= 1; $star--)
                                                            <input
                                                                id="video_rating_{{ $assignment->id }}_{{ $star }}"
                                                                type="radio"
                                                                name="video_rating"
                                                                value="{{ $star }}"
                                                                @checked((int) old('video_rating') === $star)
                                                                required
                                                            >
                                                            <label for="video_rating_{{ $assignment->id }}_{{ $star }}" title="{{ $star }} star{{ $star === 1 ? '' : 's' }}">&#9733;</label>
                                                        @endfor
                                                    </div>
                                                    <div class="demo-rating-hint">1 is lowest and 5 is highest.</div>
                                                </div>
                                            @endif
                                            <div class="demo-submit-block">
                                                <h4>Your Answer (Optional)</h4>
                                                <textarea name="answer_text" rows="4" placeholder="Type your answer here...">{{ old('answer_text') }}</textarea>
                                                <div class="muted" style="font-size: 12px;">You can write a short response here if you want.</div>
                                            </div>
                                            <div class="demo-submit-block">
                                                <h4>Upload Document (Required)</h4>
                                                <input type="file" name="submission_file" accept="*/*" required>
                                                <div class="muted" style="font-size: 12px;">This upload is required. You can upload PDF, DOCX, PPT, ZIP, video, or image file.</div>
                                            </div>
                                            <div class="demo-task-actions">
                                                <button class="btn btn-soft" type="submit">Submit Demo Task</button>
                                            </div>
                                        </form>
                                    @else
                                        <div class="upload-empty">
                                            This task was uploaded by admin.
                                            @if ($canDownloadResource)
                                                You can access the resource above, but submission is disabled until it is assigned to you.
                                            @else
                                                Submission is disabled until it is assigned to you.
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        @if ($demoAssignments->isEmpty())
                            <p class="muted">No demo tasks available yet.</p>
                        @elseif ($nextDemoTaskReadyInSeconds)
                            <div class="demo-submission-alert demo-submission-alert--success">
                                <div class="demo-submission-alert-title">
                                    <span class="demo-submission-alert-check" aria-hidden="true">&#10003;</span>
                                    <strong>Congratulations! Your task has been submitted successfully.</strong>
                                </div>
                                <p>Our team will review your response and contact you soon with the next steps.</p>
                            </div>
                        @else
                            <div class="demo-submission-alert">
                                <strong>No demo tasks are ready right now.</strong>
                                <p>Admin-assigned demo tasks will appear here as soon as they are available.</p>
                            </div>
                        @endif
                    @endforelse
                </div>
            </section>

            <section class="demo-video-slider demo-panel demo-panel--feature" data-demo-video-slider>
                <div class="demo-video-viewport">
                    <div class="demo-video-track" data-demo-video-track>
                        @forelse ($demoFeatureVideos as $index => $video)
                            @php
                                $featureOpenUrl = $video->has_uploaded_video ? route('demo-feature-video.show', $video) : $video->watch_url;
                                $featureRatio = $video->resolved_video_ratio;
                                $featureCardClass = $featureRatio === \App\Support\DemoVideoRatio::REEL ? 'demo-video--feature-reel' : '';
                            @endphp
                            <article class="demo-video-slide {{ $index === 0 ? 'active' : '' }}" data-demo-video-slide aria-hidden="{{ $index === 0 ? 'false' : 'true' }}">
                                <div class="demo-video {{ $featureCardClass }}">
                                    <div class="demo-video-cover">
                                        {{-- <span class="demo-video-badge">Feature Video {{ str_pad((string) ($video->position ?? ($index + 1)), 2, '0', STR_PAD_LEFT) }}</span> --}}
                                        <h3>{{ $video->title ?: 'Feature Video' }}</h3>
                                        <p>{{ $video->description ?: 'See how our learning platform works in minutes.' }}</p>
                                        {{-- <div class="hero-note">
                                            <span class="hero-chip">{{ $video->video_ratio_label }}</span>
                                        </div> --}}
                                        {{-- <div class="hero-note">
                                            <span class="hero-chip">Position {{ $video->position ?? ($index + 1) }}</span>
                                            <span class="hero-chip">{{ $video->has_uploaded_video ? 'Uploaded video' : 'YouTube video' }}</span>
                                            @if ($video->has_uploaded_video && $video->has_youtube_video)
                                                <span class="hero-chip">YouTube fallback ready</span>
                                            @endif
                                            <span class="hero-chip">{{ $demoVideoCount }} video{{ $demoVideoCount === 1 ? '' : 's' }} live</span>
                                        </div> --}}
                                        @if ($featureOpenUrl)
                                            <a class="btn btn-soft hero-play" href="{{ $featureOpenUrl }}" target="_blank" rel="noopener">{{ $video->has_uploaded_video ? 'Open Full Video' : 'Watch on YouTube' }}</a>
                                        @else
                                            <div class="btn btn-soft hero-play">No video source</div>
                                        @endif
                                    </div>
                                    <div class="demo-video-thumb demo-video-thumb--{{ $featureRatio }}">
                                        @if ($video->has_uploaded_video)
                                            <div class="demo-media-frame demo-media-frame--{{ $featureRatio }}">
                                                <video controls preload="metadata" controlslist="nodownload" playsinline>
                                                    <source src="{{ route('demo-feature-video.show', $video) }}" type="{{ $video->file_mime ?: 'video/mp4' }}">
                                                </video>
                                            </div>
                                        @elseif ($video->embed_url)
                                            <div class="demo-media-frame demo-media-frame--{{ $featureRatio }}">
                                                <iframe
                                                    src="{{ $video->embed_url }}&enablejsapi=1"
                                                    title="{{ $video->title ?: 'Feature Video' }}"
                                                    loading="lazy"
                                                    referrerpolicy="strict-origin-when-cross-origin"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                    allowfullscreen
                                                    data-demo-youtube-embed
                                                ></iframe>
                                            </div>
                                        @else
                                            <div class="demo-media-frame demo-media-frame--landscape">
                                                <div class="demo-empty" style="height: 100%; display: grid; place-content: center; text-align: center;">
                                                    FEATURE VIDEO
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="demo-video-slide active" data-demo-video-slide aria-hidden="false">
                                <div class="demo-video demo-video-empty">
                                    <div class="demo-video-cover">
                                        <span class="demo-video-badge">Feature Video</span>
                                        <h3>Videos Coming Soon</h3>
                                        <p>Feature videos added here will appear automatically for demo users.</p>
                                        <div class="hero-note">
                                            <span class="hero-chip">0 videos live</span>
                                            <span class="hero-chip">Dynamic slider ready</span>
                                        </div>
                                        <div class="btn btn-soft hero-play">No video yet</div>
                                    </div>
                                    <div class="demo-video-thumb demo-video-thumb--landscape">
                                        <div class="demo-media-frame demo-media-frame--landscape">
                                            <div class="demo-empty" style="height: 100%; display: grid; place-content: center; text-align: center;">
                                                FEATURE VIDEO
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                @if ($demoVideoCount > 1)
                    <div class="demo-video-nav">
                        <div class="demo-video-nav-group">
                            <button class="demo-video-arrow" type="button" data-demo-video-prev aria-label="Show previous feature video">&#8249;</button>
                            <button class="demo-video-arrow" type="button" data-demo-video-next aria-label="Show next feature video">&#8250;</button>
                        </div>
                        <div class="demo-video-dots" aria-label="Demo video slider navigation">
                            @foreach ($demoFeatureVideos as $index => $video)
                                <button
                                    class="demo-video-dot {{ $index === 0 ? 'active' : '' }}"
                                    type="button"
                                    data-demo-video-dot="{{ $index }}"
                                    aria-label="Show feature video {{ $index + 1 }}: {{ $video->title ?: 'Feature Video' }}"
                                    aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                ></button>
                            @endforeach
                        </div>
                        <div class="demo-video-counter" data-demo-video-counter>1 / {{ $demoVideoCount }}</div>
                    </div>
                @endif
            </section>

            {{-- <section class="card">
                <div class="page-head">
                    <h2>Browse Courses</h2>
                </div>
                <div class="category-head">
                    <p>Select a main category, then choose a subcategory to filter courses.</p>
                </div>
                <div class="tab-row centered" id="categoryTabs" style="margin-top: 12px;">
                    @foreach ($demoCategories as $index => $category)
                        <button class="tab-btn main-tab {{ $index === 0 ? 'active' : '' }}" type="button" data-tab="{{ $category->id }}">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
                @foreach ($demoCategories as $index => $category)
                    @php
                        $tabCourses = $category->courses
                            ->concat($category->children->flatMap->courses)
                            ->unique('id')
                            ->values();
                    @endphp
                    <div class="tab-panel {{ $index === 0 ? 'active' : '' }}" data-tab-panel="{{ $category->id }}">
                        <div class="subtab-label">Subcategories</div>
                        <div class="subtab-row" data-subtabs>
                            <button class="subtab-btn active" type="button" data-subtab="all">All</button>
                            @foreach ($category->children as $child)
                                <button class="subtab-btn" type="button" data-subtab="{{ $child->id }}">{{ $child->name }}</button>
                            @endforeach
                        </div>
                        <div class="category-divider"></div>
                        <div class="demo-course-grid">
                            @forelse ($tabCourses as $course)
                                @php
                                    $thumb = $course->thumbnail_url ?: '';
                                    $bg = $thumb
                                        ? "url('{$thumb}')"
                                        : 'linear-gradient(120deg, #1c5fca, #3aa77a)';
                                    $courseCategory = $course->subcategory?->name ?? $course->category?->name ?? $category->name;
                                    $subCategoryId = $course->subcategory?->id ? (string) $course->subcategory->id : 'none';
                                @endphp
                                <div class="demo-course-tile" data-subcat="{{ $subCategoryId }}">
                                    <div class="demo-course-top" style="background-image: {{ $bg }};">
                                        <strong>{{ $course->title }}</strong>
                                    </div>
                                    <div class="demo-course-body">
                                        <div class="muted">Category: {{ $courseCategory }}</div>
                                        <span class="badge-lock">Locked</span>
                                    </div>
                                </div>
                            @empty
                                <p class="muted">No courses in this category.</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </section> --}}

            @php
                $demoReviewCount = $demoReviewVideos->count();
            @endphp
            <section class="card demo-panel demo-panel--reviews">
                <div class="page-head">
                    <div>
                        <h2>Hear From Our Alumni</h2>
                    </div>
                </div>

                <div class="demo-video-slider demo-review-slider" data-demo-video-slider>
                    <div class="demo-video-viewport">
                        <div class="demo-video-track" data-demo-video-track>
                            @forelse ($demoReviewVideos as $index => $video)
                                @php
                                    $reviewCardClass = $video->resolved_video_ratio === \App\Support\DemoVideoRatio::REEL ? 'demo-video--review-reel' : '';
                                @endphp
                                <article class="demo-video-slide {{ $index === 0 ? 'active' : '' }}" data-demo-video-slide aria-hidden="{{ $index === 0 ? 'false' : 'true' }}">
                                    <div class="demo-video {{ $reviewCardClass }}">
                                        <div class="demo-video-cover">
                                            {{-- <span class="demo-video-badge">Review {{ str_pad((string) ($video->position ?? ($index + 1)), 2, '0', STR_PAD_LEFT) }}</span> --}}
                                            <h3>{{ $video->title ?: 'Learner Review' }}</h3>
                                            <p>{{ $video->description ?: 'YouTube review videos added in the admin panel will appear here automatically for demo users.' }}</p>
                                            <div class="demo-review-actions">
                                                {{-- <div class="hero-note">
                                                    <span class="hero-chip">{{ $video->video_ratio_label }}</span>
                                                </div> --}}
                                                {{-- <div class="hero-note">
                                                    <span class="hero-chip">Position {{ $video->position ?? ($index + 1) }}</span>
                                                    <span class="hero-chip">YouTube review</span>
                                                    <span class="hero-chip">{{ $demoReviewCount }} review{{ $demoReviewCount === 1 ? '' : 's' }} live</span>
                                                </div> --}}
                                                <a class="btn btn-soft hero-play" href="{{ $video->watch_url }}" target="_blank" rel="noopener">Watch on YouTube</a>
                                            </div>
                                        </div>
                                        <div class="demo-video-thumb demo-video-thumb--{{ $video->resolved_video_ratio }}">
                                            <div class="demo-media-frame demo-media-frame--{{ $video->resolved_video_ratio }}">
                                                <iframe
                                                    src="{{ $video->embed_url }}&enablejsapi=1"
                                                    title="{{ $video->title ?: 'Demo Review Video' }}"
                                                    loading="lazy"
                                                    referrerpolicy="strict-origin-when-cross-origin"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                    allowfullscreen
                                                    data-demo-youtube-embed
                                                ></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @empty
                                <div class="demo-video-slide active" data-demo-video-slide aria-hidden="false">
                                    <div class="demo-video demo-video-empty">
                                        <div class="demo-video-cover">
                                            <span class="demo-video-badge">Reviews</span>
                                            <h3>Review Videos Coming Soon</h3>
                                            <p>Once admin or superadmin adds YouTube review videos with positions, they will appear here in this slider.</p>
                                            <div class="hero-note">
                                                <span class="hero-chip">0 reviews live</span>
                                                <span class="hero-chip">YouTube slider ready</span>
                                            </div>
                                            <div class="btn btn-soft hero-play">No review video yet</div>
                                        </div>
                                        <div class="demo-video-thumb demo-video-thumb--landscape">
                                            <div class="demo-media-frame demo-media-frame--landscape">
                                                <div class="demo-empty" style="height: 100%; display: grid; place-content: center; text-align: center;">
                                                    DEMO REVIEWS
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    @if ($demoReviewCount > 1)
                        <div class="demo-video-nav">
                            <div class="demo-video-nav-group">
                                <button class="demo-video-arrow" type="button" data-demo-video-prev aria-label="Show previous review video">&#8249;</button>
                                <button class="demo-video-arrow" type="button" data-demo-video-next aria-label="Show next review video">&#8250;</button>
                            </div>
                            <div class="demo-video-dots" aria-label="Demo review slider navigation">
                                @foreach ($demoReviewVideos as $index => $video)
                                    <button
                                        class="demo-video-dot {{ $index === 0 ? 'active' : '' }}"
                                        type="button"
                                        data-demo-video-dot="{{ $index }}"
                                        aria-label="Show review video {{ $index + 1 }}: {{ $video->title ?: 'Demo Review Video' }}"
                                        aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                    ></button>
                                @endforeach
                            </div>
                            <div class="demo-video-counter" data-demo-video-counter>1 / {{ $demoReviewCount }}</div>
                        </div>
                    @endif
                </div>
            </section>
