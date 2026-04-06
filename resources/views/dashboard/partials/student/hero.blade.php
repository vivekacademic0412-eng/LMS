        @if ($isStudent && !empty($heroCourse))
            @php
                $heroThumb = $heroCourse['thumbnail_url'] ?? '';
                $heroBg = $heroThumb
                    ? "url('{$heroThumb}')"
                    : 'linear-gradient(120deg, #0f4dbf 0%, #1d6ed0 100%)';
            @endphp
            <section class="dash-hero with-image" style="background-image: {{ $heroBg }};">
                <div style="z-index: 1;">
                    @if ($heroKicker !== '')
                        <p class="hero-kicker">{{ $heroKicker }}</p>
                    @endif
                    <h1 class="hero-title">{{ $heroCourse['title'] ?? 'Learning Dashboard' }}</h1>
                    <p class="hero-meta">{{ $heroCourse['provider'] ?? 'LMS Academy' }}</p>
                    <a class="hero-btn" href="{{ $heroResumeRoute }}">Continue</a>
                    <p class="hero-sub">{{ $heroCourse['progress_percent'] ?? 0 }}% complete &middot; {{ $heroCourse['hours_done'] ?? 0 }}h of {{ $heroCourse['hours_total'] ?? 0 }}h</p>
                </div>
                <div class="hero-ring">
                    <b>{{ $heroCourse['progress_percent'] ?? 0 }}%</b>
                    <span>Done</span>
                </div>
            </section>
        @endif
