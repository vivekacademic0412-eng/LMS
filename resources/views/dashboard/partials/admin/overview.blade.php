@if (in_array($user->role, [\App\Models\User::ROLE_SUPERADMIN, \App\Models\User::ROLE_ADMIN], true))
    <section class="panel-inline-hero">
        <h3>Admin Panel</h3>
        <p>{{ $panelDescription }}</p>
    </section>
    <section class="stats-grid">
        @foreach ($overviewCards as $card)
            <div class="stat-box">
                <div class="stat-icon">{{ $card['code'] }}</div>
                <div>
                    <b>{{ $card['value'] }}{{ $card['suffix'] ?? '' }}</b>
                    <span>{{ $card['label'] }}</span>
                </div>
            </div>
        @endforeach
    </section>
@endif
