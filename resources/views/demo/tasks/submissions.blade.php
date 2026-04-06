@extends('layouts.app')
@php
    $roleLabels = \App\Models\User::roleOptions();
@endphp
@section('content')
    <style>
        .submissions-page { display: grid; gap: 16px; }
        .submissions-hero {
            background: linear-gradient(120deg, #0f4dbf 0%, #1d6ed0 100%);
            color: #fff;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 18px 40px rgba(15, 55, 120, 0.18);
        }
        .submissions-hero h1 { margin: 0; font-size: 30px; line-height: 1.1; }
        .submissions-hero p { margin: 8px 0 0; opacity: 0.92; }
        .submissions-stats { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 14px; }
        .submissions-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 6px 12px;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.22);
            font-size: 12px;
            font-weight: 700;
        }
        .submission-list { display: grid; gap: 14px; }
        .submission-card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 14px;
            box-shadow: 0 10px 22px rgba(18, 42, 86, 0.06);
            display: grid;
            gap: 10px;
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }
        .submission-card:hover {
            transform: translateY(-2px);
            border-color: #c6d9f7;
            box-shadow: 0 16px 32px rgba(18, 42, 86, 0.1);
        }
        .submission-head {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            align-items: start;
        }
        .submission-title { display: grid; gap: 6px; }
        .submission-title strong {
            font-size: 18px;
            color: #0f2d57;
            line-height: 1.25;
        }
        .submission-badges { display: flex; flex-wrap: wrap; gap: 8px; }
        .submission-badges .demo-status {
            border: 1px solid #d8e4f6;
            background: #f7fbff;
            color: #244671;
            font-weight: 700;
        }
        .submission-body {
            display: grid;
            gap: 12px;
        }
        .submission-secondary-row {
            display: grid;
            gap: 12px;
            grid-template-columns: minmax(0, 1.35fr) minmax(260px, 0.65fr);
        }
        .submission-panel {
            border: 1px solid #dce4f1;
            border-radius: 12px;
            background: #f8fbff;
            padding: 12px;
            display: grid;
            gap: 8px;
        }
        .submission-answer-panel {
            align-self: start;
        }
        .submission-file-panel {
            align-self: start;
        }
        .submission-panel.alt { background: #fff; }
        .submission-panel h4 {
            margin: 0;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #6c7c94;
        }
        .selected-user-card {
            border: 1px solid #d7deea;
            border-radius: 16px;
            background:
                radial-gradient(circle at top right, rgba(29, 110, 208, 0.12), rgba(29, 110, 208, 0) 36%),
                linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
            padding: 18px;
            display: grid;
            gap: 14px;
            box-shadow: 0 10px 22px rgba(18, 42, 86, 0.05);
        }
        .selected-user-card h3 {
            margin: 0;
            color: #102849;
            font-size: 24px;
        }
        .selected-user-card p {
            margin: 6px 0 0;
            color: #5d6f89;
            font-size: 14px;
            line-height: 1.65;
        }
        .selected-user-grid {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        }
        .submission-user-grid {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }
        .selected-user-stat,
        .submission-user-stat {
            border: 1px solid #dbe5f4;
            border-radius: 12px;
            background: #fff;
            padding: 10px 12px;
            display: grid;
            gap: 4px;
        }
        .selected-user-stat span,
        .submission-user-stat span {
            color: #6b7c95;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .selected-user-stat strong,
        .submission-user-stat strong {
            color: #102849;
            font-size: 15px;
            line-height: 1.35;
            word-break: break-word;
        }
        .submission-user-stat strong.submission-rating {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .submission-rating .stars {
            color: #f4b400;
            letter-spacing: 0.05em;
            font-size: 13px;
            line-height: 1;
        }
        .submission-rating .score {
            color: #365078;
            font-size: 12px;
            font-weight: 700;
        }
        .submission-answer {
            white-space: pre-wrap;
            line-height: 1.6;
            color: #31415b;
            font-size: 14px;
            max-height: 140px;
            overflow: auto;
            padding-right: 4px;
        }
        .submission-answer::-webkit-scrollbar {
            width: 8px;
        }
        .submission-answer::-webkit-scrollbar-thumb {
            background: #c3d3ec;
            border-radius: 999px;
        }
        .submission-pagination-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            padding: 14px 16px;
            border: 1px solid #dce4f1;
            border-radius: 16px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 10px 20px rgba(18, 42, 86, 0.05);
        }
        .submission-pagination-copy {
            display: grid;
            gap: 4px;
        }
        .submission-pagination-copy strong {
            color: #102849;
            font-size: 15px;
        }
        .submission-pagination-copy span {
            color: #5e6d84;
            font-size: 13px;
        }
        .submission-pagination-links {
            margin-left: auto;
            display: flex;
            justify-content: flex-end;
        }
        .submission-pagination-links .pagination {
            justify-content: flex-end;
        }
        .submission-user-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .submission-user-meta span {
            display: inline-flex;
            align-items: center;
            min-height: 30px;
            padding: 0 10px;
            border-radius: 999px;
            background: #f2f7ff;
            border: 1px solid #d9e6f7;
            color: #5e6d84;
            font-size: 12px;
            font-weight: 700;
        }
        .submission-footer-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .submission-file {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            justify-content: space-between;
        }
        .submission-file .name {
            color: #425066;
            font-size: 13px;
            word-break: break-word;
        }
        @media (max-width: 900px) {
            .submission-secondary-row { grid-template-columns: 1fr; }
            .submission-pagination-bar {
                align-items: stretch;
            }
            .submission-pagination-links {
                margin-left: 0;
            }
        }
        @media (max-width: 640px) {
            .submission-user-grid,
            .selected-user-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="submissions-page">
        <section class="submissions-hero">
            <h1>Demo Task Submissions</h1>
            <p>Review every shared-login demo submission with the student details entered at submission time so admin and superadmin can identify the correct learner quickly.</p>
            <div class="submissions-stats">
                <span class="submissions-pill">{{ $submissions->total() }} total submissions</span>
                <span class="submissions-pill">{{ $submissions->count() }} on this page</span>
                @if (!empty($selectedUser))
                    <span class="submissions-pill">Filtered shared login: {{ $selectedUser->name }}</span>
                    <span class="submissions-pill">{{ $selectedUser->email }}</span>
                    <a class="submissions-pill" href="{{ route('demo-tasks.submissions-page') }}">Clear filter</a>
                @endif
            </div>
        </section>

        @if (!empty($selectedUser) && !empty($selectedUserStats))
            <section class="card">
                <div class="selected-user-card">
                    <div>
                        <h3>{{ $selectedUser->name }}</h3>
                        <p>Filtered shared demo login for quick review, routing, and follow-up across multiple student submissions.</p>
                    </div>
                    <div class="submission-badges">
                        <span class="demo-status">{{ $roleLabels[$selectedUser->role] ?? 'Demo User' }}</span>
                        <span class="demo-status">{{ $selectedUser->is_active ? 'Active Account' : 'Inactive Account' }}</span>
                        <span class="demo-status">{{ $selectedUserStats['submissions'] }} submitted task{{ $selectedUserStats['submissions'] === 1 ? '' : 's' }}</span>
                    </div>
                    <div class="selected-user-grid">
                        <div class="selected-user-stat">
                            <span>Email</span>
                            <strong>{{ $selectedUser->email }}</strong>
                        </div>
                        <div class="selected-user-stat">
                            <span>Joined</span>
                            <strong>{{ optional($selectedUser->created_at)->format('M d, Y') ?: 'Recently added' }}</strong>
                        </div>
                        <div class="selected-user-stat">
                            <span>Assigned Tasks</span>
                            <strong>{{ $selectedUserStats['assignments'] }}</strong>
                        </div>
                        <div class="selected-user-stat">
                            <span>Submitted Tasks</span>
                            <strong>{{ $selectedUserStats['submissions'] }}</strong>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if ($submissions->isNotEmpty())
            <div class="submission-list">
                @foreach ($submissions as $submission)
                    @php
                        $demoUser = $submission->assignment?->user;
                        $userId = $demoUser?->id;
                        $assignedCount = $userId ? ($userAssignmentCounts[$userId] ?? 0) : 0;
                        $submittedCount = $userId ? ($userSubmissionCounts[$userId] ?? 0) : 0;
                        $videoRating = (int) ($submission->video_rating ?? 0);
                    @endphp
                    <article class="submission-card">
                        <div class="submission-head">
                            <div class="submission-title">
                                <strong>{{ $submission->assignment?->demoTask?->title ?? 'Demo Task' }}</strong>
                                <div class="submission-badges">
                                    <span class="demo-status">Student: {{ $submission->participant_name ?: '-' }}</span>
                                    @if ($submission->participant_email)
                                        <span class="demo-status">{{ $submission->participant_email }}</span>
                                    @endif
                                    <span class="demo-status">Shared login: {{ $demoUser?->name ?? 'Demo User' }}</span>
                                    <span class="demo-status">Submitted: {{ $submission->submitted_at?->format('M d, Y h:i A') ?? '-' }}</span>
                                    @if ($videoRating > 0)
                                        <span class="demo-status">Rating: {{ $videoRating }}/5</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="submission-body">
                            <div class="submission-panel alt">
                                <h4>Student Personal Info</h4>
                                <div class="submission-user-grid">
                                    <div class="submission-user-stat">
                                        <span>Student Name</span>
                                        <strong>{{ $submission->participant_name ?: '-' }}</strong>
                                    </div>
                                    <div class="submission-user-stat">
                                        <span>Gmail / Email</span>
                                        <strong>{{ $submission->participant_email ?: '-' }}</strong>
                                    </div>
                                    <div class="submission-user-stat">
                                        <span>Phone Number</span>
                                        <strong>{{ $submission->participant_phone ?: '-' }}</strong>
                                    </div>
                                    <div class="submission-user-stat">
                                        <span>Shared Login</span>
                                        <strong>{{ $demoUser?->name ?? '-' }}</strong>
                                    </div>
                                    <div class="submission-user-stat">
                                        <span>Demo Role</span>
                                        <strong>{{ $demoUser ? ($roleLabels[$demoUser->role] ?? 'Demo User') : '-' }}</strong>
                                    </div>
                                    <div class="submission-user-stat">
                                        <span>Total Submitted</span>
                                        <strong>{{ $submittedCount }}</strong>
                                    </div>
                                    <div class="submission-user-stat">
                                        <span>Video Rating</span>
                                        @if ($videoRating > 0)
                                            <strong class="submission-rating">
                                                <span class="stars">{{ str_repeat('★', $videoRating) }}</span>
                                                <span class="score">{{ $videoRating }}/5</span>
                                            </strong>
                                        @else
                                            <strong>-</strong>
                                        @endif
                                    </div>
                                </div>
                                <div class="submission-footer-row">
                                    <div class="submission-user-meta">
                                        <span>{{ $assignedCount }} assigned task{{ $assignedCount === 1 ? '' : 's' }}</span>
                                        <span>Assigned by {{ $submission->assignment?->assigner?->name ?? 'Admin' }}</span>
                                    </div>
                                    @if ($demoUser)
                                        <a class="btn btn-soft" href="{{ route('demo-tasks.submissions-page', ['user_id' => $demoUser->id]) }}">View Shared Login History</a>
                                    @endif
                                </div>
                            </div>

                            <div class="submission-secondary-row">
                                <div class="submission-panel submission-answer-panel">
                                    <h4>Answer</h4>
                                    @if ($submission->answer_text)
                                        <div class="submission-answer">{{ $submission->answer_text }}</div>
                                    @else
                                        <div class="muted">No text answer provided.</div>
                                    @endif
                                </div>
                                <div class="submission-panel alt submission-file-panel">
                                    <h4>Uploaded Document</h4>
                                    <div class="submission-file">
                                        <div class="name">{{ $submission->file_name ?: 'No uploaded document.' }}</div>
                                        @if ($submission->file_path)
                                            <a class="btn btn-soft" href="{{ route('demo-tasks.submissions.download', $submission) }}">Download</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="submission-pagination-bar">
                <div class="submission-pagination-copy">
                    <strong>Page {{ $submissions->currentPage() }} of {{ $submissions->lastPage() }}</strong>
                    <span>Showing {{ $submissions->firstItem() }} to {{ $submissions->lastItem() }} of {{ $submissions->total() }} submissions</span>
                </div>
                <div class="submission-pagination-links">
                    {{ $submissions->onEachSide(1)->links('pagination.custom') }}
                </div>
            </div>
        @else
            <div class="card">
                <div class="demo-empty">No demo submissions available yet.</div>
            </div>
        @endif
    </div>
@endsection
