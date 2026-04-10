@php
    $stats = collect($studentAnalytics['stats'] ?? []);
    $weekly = $studentAnalytics['weekly'] ?? [];
    $weeklyViews = is_array($weekly['views'] ?? null) ? $weekly['views'] : [];
    $defaultWeeklyView = (string) ($weekly['default_view'] ?? (array_key_first($weeklyViews) ?? 'hours'));
    if (! array_key_exists($defaultWeeklyView, $weeklyViews) && ! empty($weeklyViews)) {
        $defaultWeeklyView = (string) array_key_first($weeklyViews);
    }

    $completion = $studentAnalytics['completion'] ?? [];
    $radar = $studentAnalytics['radar'] ?? [];
    $heatmap = $studentAnalytics['heatmap'] ?? [];
    $quiz = $studentAnalytics['quiz'] ?? [];
    $streak = $studentAnalytics['streak'] ?? [];

    $completionPercent = max(0, min(100, (int) ($completion['overall_percent'] ?? 0)));
    $completionSegments = collect($completion['segments'] ?? []);
    $courseProgressSeries = collect($completion['series'] ?? []);
    $quizCourseSeries = collect($quiz['course_series'] ?? []);
    $radarSeries = collect($radar['series'] ?? []);
    $heatmapWeeks = collect($heatmap['weeks'] ?? []);
    $tracker = collect($streak['tracker'] ?? []);

    $donutStops = [];
    $runningPercent = 0.0;
    foreach ($completionSegments as $segment) {
        $segmentPercent = max(0.0, (float) ($segment['percent'] ?? 0));
        if ($segmentPercent <= 0) {
            continue;
        }

        $start = $runningPercent;
        $runningPercent += $segmentPercent;
        $donutStops[] = ($segment['color'] ?? '#dfe7f5').' '.$start.'% '.$runningPercent.'%';
    }

    $donutBackground = ! empty($donutStops)
        ? 'conic-gradient('.implode(', ', $donutStops).')'
        : 'conic-gradient(#dfe7f5 0% 100%)';
@endphp

<section class="dashboard-section student-progress-dashboard" data-student-progress-dashboard>
    <div class="section-head">
        <div>
            <h2>Student Progress Dashboard</h2>
            <p>Visual learning activity, course completion, quiz performance, streak momentum, and category growth from your current LMS records.</p>
        </div>
    </div>

    <div class="student-progress-stat-grid">
        @foreach ($stats as $stat)
            <article class="student-progress-stat-card student-progress-stat-card--{{ $stat['tone'] ?? 'blue' }}">
                <span class="student-progress-stat-label">{{ $stat['label'] }}</span>
                <strong
                    class="student-progress-stat-value"
                    data-countup-value="{{ $stat['value'] ?? 0 }}"
                    data-countup-decimals="{{ $stat['decimals'] ?? 0 }}"
                    data-countup-suffix="{{ $stat['suffix'] ?? '' }}"
                >
                    {{ number_format((float) ($stat['value'] ?? 0), (int) ($stat['decimals'] ?? 0)) }}{{ $stat['suffix'] ?? '' }}
                </strong>
                <p>{{ $stat['caption'] ?? '' }}</p>
            </article>
        @endforeach
    </div>

    <div class="student-progress-visual-grid">
        <article class="student-progress-card student-progress-card--wide" data-weekly-chart>
            <div class="student-progress-card-head">
                <div>
                    <span class="student-progress-kicker">Line Chart</span>
                    <h3>Weekly Learning Activity</h3>
                </div>
                <div class="student-progress-toggle-group">
                    @foreach ($weeklyViews as $viewKey => $view)
                        <button
                            type="button"
                            class="student-progress-toggle {{ $viewKey === $defaultWeeklyView ? 'is-active' : '' }}"
                            data-weekly-button="{{ $viewKey }}"
                        >
                            {{ $view['label'] ?? ucfirst(str_replace('_', ' ', $viewKey)) }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="student-progress-note">
                Hours are estimated from completed learning items.
                {{ !empty($quiz['derived_from_reviews']) ? 'Some older quiz scores are still derived from review outcomes until numeric scoring exists for those attempts.' : 'Quiz scores now use stored numeric results from scored quiz attempts.' }}
            </div>

            <div class="student-weekly-shell">
                <div class="student-weekly-stage">
                    @foreach ($weeklyViews as $viewKey => $view)
                        @php
                            $series = collect($view['series'] ?? [])->values();
                            $chartWidth = 100;
                            $chartHeight = 60;
                            $pointCount = max(1, $series->count() - 1);
                            $maxValue = max(1.0, (float) ($view['max'] ?? 1));
                            $dotPoints = $series->map(function (array $point, int $index) use ($pointCount, $chartWidth, $chartHeight, $maxValue): array {
                                $x = $pointCount > 0 ? round(($index / $pointCount) * $chartWidth, 2) : 50.0;
                                $scaledHeight = ((float) ($point['value'] ?? 0) / $maxValue) * 48;
                                $y = max(6.0, min((float) $chartHeight - 6, round($chartHeight - $scaledHeight - 6, 2)));

                                return [
                                    'x' => $x,
                                    'y' => $y,
                                    'value' => $point['value'] ?? 0,
                                    'label' => $point['label'] ?? '',
                                    'full_label' => $point['full_label'] ?? '',
                                    'tooltip' => $point['tooltip'] ?? '',
                                    'is_today' => ! empty($point['is_today']),
                                ];
                            });
                            $linePoints = $dotPoints->map(fn (array $point): string => $point['x'].','.$point['y'])->implode(' ');
                            $areaPoints = '0,'.$chartHeight.' '.$linePoints.' '.$chartWidth.','.$chartHeight;
                        @endphp
                        <div class="student-weekly-plot {{ $viewKey === $defaultWeeklyView ? 'is-active' : '' }}" data-weekly-plot="{{ $viewKey }}">
                            <svg viewBox="0 0 100 60" aria-hidden="true">
                                @foreach ([12, 24, 36, 48] as $gridLine)
                                    <line x1="0" y1="{{ $gridLine }}" x2="100" y2="{{ $gridLine }}" class="student-weekly-grid-line"></line>
                                @endforeach
                                @if ($dotPoints->isNotEmpty())
                                    <polygon points="{{ $areaPoints }}" class="student-weekly-area"></polygon>
                                    <polyline points="{{ $linePoints }}" class="student-weekly-line"></polyline>
                                    @foreach ($dotPoints as $point)
                                        <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="1.9" class="student-weekly-dot {{ $point['is_today'] ? 'is-today' : '' }}">
                                            <title>{{ $point['full_label'] }}: {{ $point['tooltip'] }}</title>
                                        </circle>
                                    @endforeach
                                @endif
                            </svg>
                        </div>
                    @endforeach
                </div>

                <div class="student-weekly-summaries">
                    @foreach ($weeklyViews as $viewKey => $view)
                        <div class="student-weekly-summary {{ $viewKey === $defaultWeeklyView ? 'is-active' : '' }}" data-weekly-summary="{{ $viewKey }}">
                            <div class="student-progress-inline-stat">
                                <span>{{ $view['summary_label'] ?? 'Summary' }}</span>
                                <strong>{{ number_format((float) ($view['summary_value'] ?? 0), (int) ($view['summary_decimals'] ?? 0)) }}{{ $view['summary_suffix'] ?? '' }}</strong>
                            </div>
                            <div class="student-progress-inline-stat">
                                <span>{{ $view['secondary_label'] ?? 'Secondary' }}</span>
                                <strong>{{ number_format((float) ($view['secondary_value'] ?? 0), (int) ($view['secondary_decimals'] ?? 0)) }}{{ $view['secondary_suffix'] ?? '' }}</strong>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @php
                $defaultSeries = collect($weeklyViews[$defaultWeeklyView]['series'] ?? []);
            @endphp
            <div class="student-weekly-axis">
                @foreach ($defaultSeries as $point)
                    <div class="{{ ! empty($point['is_today']) ? 'is-today' : '' }}">
                        <span>{{ $point['label'] ?? '' }}</span>
                        <strong>{{ $point['full_label'] ?? '' }}</strong>
                    </div>
                @endforeach
            </div>
        </article>

        <article class="student-progress-card">
            <div class="student-progress-card-head">
                <div>
                    <span class="student-progress-kicker">Donut Chart</span>
                    <h3>Overall Progress Breakdown</h3>
                </div>
                <strong class="student-progress-score">{{ $completionPercent }}%</strong>
            </div>

            <div class="student-progress-note">
                Course state split across completed, in progress, and not started learning.
            </div>

            <div class="student-donut-layout">
                <div class="student-donut-chart" style="background: {{ $donutBackground }};">
                    <div class="student-donut-inner">
                        <strong>{{ $completionPercent }}%</strong>
                        <span>{{ $completion['completed_items'] ?? 0 }} / {{ $completion['total_items'] ?? 0 }} items</span>
                    </div>
                </div>

                <div class="student-donut-legend">
                    @forelse ($completionSegments as $segment)
                        <div class="student-donut-legend-row">
                            <div class="student-donut-legend-copy">
                                <i style="background: {{ $segment['color'] ?? '#dfe7f5' }}"></i>
                                <span>{{ $segment['label'] ?? 'State' }}</span>
                            </div>
                            <strong>{{ $segment['value'] ?? 0 }}</strong>
                        </div>
                    @empty
                        <div class="submission-empty">Enroll in a course to unlock progress breakdowns.</div>
                    @endforelse
                </div>
            </div>
        </article>

        <article class="student-progress-card">
            <div class="student-progress-card-head">
                <div>
                    <span class="student-progress-kicker">Radar Chart</span>
                    <h3>Category Competency vs Target</h3>
                </div>
                <strong class="student-progress-score">{{ $radarSeries->count() }}</strong>
            </div>

            <div class="student-progress-note">
                {{ $radar['goal_label'] ?? 'Target = 100% completion across active categories' }}
            </div>

            @if ($radarSeries->count() >= 3)
                @php
                    $radarCenter = 110;
                    $radarRadius = 72;
                    $radarAxes = $radarSeries->values()->map(function (array $point, int $index) use ($radarSeries, $radarCenter, $radarRadius): array {
                        $angle = deg2rad(-90 + ((360 / max(1, $radarSeries->count())) * $index));
                        $axisX = $radarCenter + cos($angle) * $radarRadius;
                        $axisY = $radarCenter + sin($angle) * $radarRadius;
                        $labelX = $radarCenter + cos($angle) * ($radarRadius + 24);
                        $labelY = $radarCenter + sin($angle) * ($radarRadius + 24);
                        $valueX = $radarCenter + cos($angle) * ($radarRadius * ((int) ($point['value'] ?? 0) / 100));
                        $valueY = $radarCenter + sin($angle) * ($radarRadius * ((int) ($point['value'] ?? 0) / 100));

                        return [
                            'label' => $point['label'] ?? 'Category',
                            'courses' => $point['courses'] ?? 0,
                            'axis_x' => round($axisX, 2),
                            'axis_y' => round($axisY, 2),
                            'label_x' => round($labelX, 2),
                            'label_y' => round($labelY, 2),
                            'value_x' => round($valueX, 2),
                            'value_y' => round($valueY, 2),
                            'anchor' => $labelX > ($radarCenter + 10) ? 'start' : ($labelX < ($radarCenter - 10) ? 'end' : 'middle'),
                            'value' => (int) ($point['value'] ?? 0),
                        ];
                    });

                    $targetPolygon = $radarAxes->map(fn (array $axis): string => $axis['axis_x'].','.$axis['axis_y'])->implode(' ');
                    $valuePolygon = $radarAxes->map(fn (array $axis): string => $axis['value_x'].','.$axis['value_y'])->implode(' ');
                @endphp

                <div class="student-radar-shell">
                    <svg viewBox="0 0 220 220" aria-hidden="true">
                        @foreach ([0.25, 0.5, 0.75, 1] as $scale)
                            @php
                                $ringPoints = $radarAxes
                                    ->map(function (array $axis) use ($radarCenter, $scale): string {
                                        $scaledX = $radarCenter + (($axis['axis_x'] - $radarCenter) * $scale);
                                        $scaledY = $radarCenter + (($axis['axis_y'] - $radarCenter) * $scale);

                                        return round($scaledX, 2).','.round($scaledY, 2);
                                    })
                                    ->implode(' ');
                            @endphp
                            <polygon points="{{ $ringPoints }}" class="student-radar-ring"></polygon>
                        @endforeach

                        @foreach ($radarAxes as $axis)
                            <line x1="{{ $radarCenter }}" y1="{{ $radarCenter }}" x2="{{ $axis['axis_x'] }}" y2="{{ $axis['axis_y'] }}" class="student-radar-axis"></line>
                        @endforeach

                        <polygon points="{{ $targetPolygon }}" class="student-radar-target"></polygon>
                        <polygon points="{{ $valuePolygon }}" class="student-radar-value"></polygon>

                        @foreach ($radarAxes as $axis)
                            <circle cx="{{ $axis['value_x'] }}" cy="{{ $axis['value_y'] }}" r="3" class="student-radar-dot"></circle>
                            <text x="{{ $axis['label_x'] }}" y="{{ $axis['label_y'] }}" text-anchor="{{ $axis['anchor'] }}" class="student-radar-label">
                                {{ \Illuminate\Support\Str::limit($axis['label'], 12) }}
                            </text>
                        @endforeach
                    </svg>
                </div>

                <div class="student-radar-legend">
                    @foreach ($radarSeries as $point)
                        <div class="student-radar-legend-row">
                            <span>{{ $point['label'] ?? 'Category' }}</span>
                            <strong>{{ $point['value'] ?? 0 }}%</strong>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="submission-empty">Enroll across at least three course categories to unlock the radar view.</div>
            @endif
        </article>

        <article class="student-progress-card student-progress-card--wide">
            <div class="student-progress-card-head">
                <div>
                    <span class="student-progress-kicker">Heatmap</span>
                    <h3>6-Month Learning Activity Calendar</h3>
                </div>
                <strong class="student-progress-score">{{ $heatmap['active_days'] ?? 0 }}</strong>
            </div>

            <div class="student-progress-note">
                GitHub-style activity view for completed items and submitted coursework from {{ $heatmap['start_label'] ?? '' }} to {{ $heatmap['end_label'] ?? '' }}.
            </div>

            <div class="student-heatmap-scroll">
                <div class="student-heatmap-months">
                    <span aria-hidden="true"></span>
                    @foreach ($heatmapWeeks as $week)
                        <span>{{ $week['month_label'] ?? '' }}</span>
                    @endforeach
                </div>

                <div class="student-heatmap-body">
                    <div class="student-heatmap-weekdays">
                        <span>Mon</span>
                        <span>Tue</span>
                        <span>Wed</span>
                        <span>Thu</span>
                        <span>Fri</span>
                        <span>Sat</span>
                        <span>Sun</span>
                    </div>

                    <div class="student-heatmap-grid">
                        @forelse ($heatmapWeeks as $week)
                            <div class="student-heatmap-week">
                                @foreach (($week['days'] ?? collect()) as $day)
                                    <div
                                        class="student-heatmap-cell level-{{ $day['level'] ?? 0 }} {{ empty($day['is_in_range']) ? 'is-outside' : '' }} {{ !empty($day['is_today']) ? 'is-today' : '' }}"
                                        title="{{ $day['weekday'] ?? '' }}, {{ $day['date'] ?? '' }}: {{ $day['activity_count'] ?? 0 }} activity item(s){{ isset($day['hours']) ? ' | '.number_format((float) $day['hours'], 1).'h estimated' : '' }}"
                                    ></div>
                                @endforeach
                            </div>
                        @empty
                            <div class="submission-empty">No activity yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="student-streak-legend">
                <span>Less</span>
                <div class="student-streak-legend-steps">
                    <i class="level-0"></i>
                    <i class="level-1"></i>
                    <i class="level-2"></i>
                    <i class="level-3"></i>
                    <i class="level-4"></i>
                </div>
                <span>More</span>
            </div>
        </article>
    </div>

    <div class="student-progress-detail-grid">
        <article class="student-progress-card">
            <div class="student-progress-card-head">
                <div>
                    <span class="student-progress-kicker">Progress Bars</span>
                    <h3>Per-Course Completion</h3>
                </div>
                <strong class="student-progress-score">{{ $completion['course_count'] ?? 0 }}</strong>
            </div>

            <div class="student-progress-note">
                Animated fills show how far each active course has moved toward completion.
            </div>

            <div class="student-progress-bar-list">
                @forelse ($courseProgressSeries as $course)
                    <div class="student-progress-bar-row">
                        <div class="student-progress-bar-headline">
                            <div>
                                <strong>{{ \Illuminate\Support\Str::limit($course['title'] ?? 'Course', 28) }}</strong>
                                <span>{{ $course['category'] ?? 'General' }} | {{ number_format((float) ($course['hours_done'] ?? 0), 1) }}h / {{ number_format((float) ($course['hours_total'] ?? 0), 1) }}h</span>
                            </div>
                            <em>{{ $course['percent'] ?? 0 }}%</em>
                        </div>
                        <div class="student-progress-fill-track">
                            <span data-fill-width="{{ max(0, min(100, (int) ($course['percent'] ?? 0))) }}%"></span>
                        </div>
                    </div>
                @empty
                    <div class="submission-empty">Your enrolled courses will appear here once progress starts.</div>
                @endforelse
            </div>
        </article>

        <article class="student-progress-card">
            <div class="student-progress-card-head">
                <div>
                    <span class="student-progress-kicker">Quiz Score Bars</span>
                    <h3>Per-Course Quiz Performance</h3>
                </div>
                <strong class="student-progress-score">{{ $quiz['average_score'] ?? 0 }}</strong>
            </div>

            <div class="student-progress-note">
                {{ !empty($quiz['derived_from_reviews']) ? 'Course quiz bars mix numeric quiz scores with review-based fallback values for older submissions.' : 'Course quiz bars are based on stored numeric quiz scores.' }}
            </div>

            <div class="student-progress-bar-list">
                @forelse ($quizCourseSeries as $courseQuiz)
                    <div class="student-progress-bar-row">
                        <div class="student-progress-bar-headline">
                            <div>
                                <strong>{{ \Illuminate\Support\Str::limit($courseQuiz['title'] ?? 'Course', 28) }}</strong>
                                <span>{{ $courseQuiz['category'] ?? 'General' }} | {{ $courseQuiz['attempts'] ?? 0 }} quiz attempt(s)</span>
                            </div>
                            <em>{{ $courseQuiz['score'] ?? 0 }}%</em>
                        </div>
                        <div class="student-progress-fill-track student-progress-fill-track--quiz">
                            <span data-fill-width="{{ max(0, min(100, (int) ($courseQuiz['score'] ?? 0))) }}%"></span>
                        </div>
                    </div>
                @empty
                    <div class="submission-empty">Submit and review quizzes to build per-course quiz bars.</div>
                @endforelse
            </div>

            <div class="student-progress-inline-stats">
                <div class="student-progress-inline-stat">
                    <span>Reviewed</span>
                    <strong>{{ $quiz['reviewed'] ?? 0 }}</strong>
                </div>
                <div class="student-progress-inline-stat">
                    <span>Pending</span>
                    <strong>{{ $quiz['pending'] ?? 0 }}</strong>
                </div>
                <div class="student-progress-inline-stat">
                    <span>Revision</span>
                    <strong>{{ $quiz['revision_requested'] ?? 0 }}</strong>
                </div>
                <div class="student-progress-inline-stat">
                    <span>Total Attempts</span>
                    <strong>{{ $quiz['attempted'] ?? 0 }}</strong>
                </div>
            </div>
        </article>

        <article class="student-progress-card student-progress-card--wide">
            <div class="student-progress-card-head">
                <div>
                    <span class="student-progress-kicker">14-Day Streak</span>
                    <h3>Learning Streak Tracker</h3>
                </div>
                <strong class="student-progress-score">{{ $streak['current'] ?? 0 }}d</strong>
            </div>

            <div class="student-progress-note">
                A learning day counts when you complete content or submit coursework.
            </div>

            <div class="student-progress-inline-stats student-progress-inline-stats--three">
                <div class="student-progress-inline-stat">
                    <span>Current Streak</span>
                    <strong>{{ $streak['current'] ?? 0 }}d</strong>
                </div>
                <div class="student-progress-inline-stat">
                    <span>Longest Streak</span>
                    <strong>{{ $streak['longest'] ?? 0 }}d</strong>
                </div>
                <div class="student-progress-inline-stat">
                    <span>Active Days in 14</span>
                    <strong>{{ $streak['tracker_active'] ?? 0 }}</strong>
                </div>
            </div>

            <div class="student-streak-tracker-grid">
                @forelse ($tracker as $day)
                    <div class="student-streak-tracker-day {{ !empty($day['is_active']) ? 'is-active' : '' }} {{ !empty($day['is_today']) ? 'is-today' : '' }}">
                        <span>{{ $day['weekday'] ?? '' }}</span>
                        <strong>{{ $day['day'] ?? '' }}</strong>
                    </div>
                @empty
                    <div class="submission-empty">Your streak tracker will appear once you start learning.</div>
                @endforelse
            </div>
        </article>
    </div>
</section>

@once
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var formatCount = function (value, decimals, suffix) {
                return Number(value).toLocaleString(undefined, {
                    minimumFractionDigits: decimals,
                    maximumFractionDigits: decimals
                }) + suffix;
            };

            document.querySelectorAll('[data-student-progress-dashboard]').forEach(function (dashboard) {
                dashboard.querySelectorAll('[data-weekly-chart]').forEach(function (chart) {
                    var activateView = function (viewKey) {
                        chart.querySelectorAll('[data-weekly-button]').forEach(function (button) {
                            button.classList.toggle('is-active', button.getAttribute('data-weekly-button') === viewKey);
                        });

                        chart.querySelectorAll('[data-weekly-plot]').forEach(function (plot) {
                            plot.classList.toggle('is-active', plot.getAttribute('data-weekly-plot') === viewKey);
                        });

                        chart.querySelectorAll('[data-weekly-summary]').forEach(function (summary) {
                            summary.classList.toggle('is-active', summary.getAttribute('data-weekly-summary') === viewKey);
                        });
                    };

                    chart.querySelectorAll('[data-weekly-button]').forEach(function (button) {
                        button.addEventListener('click', function () {
                            activateView(button.getAttribute('data-weekly-button'));
                        });
                    });
                });

                dashboard.querySelectorAll('[data-countup-value]').forEach(function (node, index) {
                    var target = parseFloat(node.getAttribute('data-countup-value') || '0');
                    var decimals = parseInt(node.getAttribute('data-countup-decimals') || '0', 10);
                    var suffix = node.getAttribute('data-countup-suffix') || '';
                    var duration = 900 + (index * 120);
                    var startTime = null;

                    node.textContent = formatCount(0, decimals, suffix);

                    var tick = function (timestamp) {
                        if (!startTime) {
                            startTime = timestamp;
                        }

                        var progress = Math.min((timestamp - startTime) / duration, 1);
                        var eased = 1 - Math.pow(1 - progress, 3);
                        node.textContent = formatCount(target * eased, decimals, suffix);

                        if (progress < 1) {
                            window.requestAnimationFrame(tick);
                        }
                    };

                    window.requestAnimationFrame(tick);
                });

                window.setTimeout(function () {
                    dashboard.querySelectorAll('[data-fill-width]').forEach(function (bar, index) {
                        window.setTimeout(function () {
                            bar.style.width = bar.getAttribute('data-fill-width') || '0%';
                        }, 100 + (index * 50));
                    });
                }, 180);
            });
        });
    </script>
@endonce
