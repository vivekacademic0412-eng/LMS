@if ($submission->submission_type === \App\Models\CourseSessionItem::TYPE_QUIZ && $submission->quizAnswers->isNotEmpty())
    <div class="meta-stack">
        <strong>{{ $submission->scoreLabel() }} @if ($submission->score_percent !== null) ({{ $submission->scorePercentLabel() }}) @endif</strong>
        <span class="muted">{{ $submission->passStatusLabel() }} | Attempt {{ $submission->attempt_number ?? 1 }}</span>
        @foreach ($submission->quizAnswers->take(3) as $answer)
            <div class="submission-copy">
                <strong>Q{{ $loop->iteration }}:</strong>
                {{ \Illuminate\Support\Str::limit($answer->question?->prompt ?? 'Question', 72) }}
                <br>
                <span class="muted">Answer:</span>
                {{ $answer->question?->displaySubmittedAnswer($answer->answer_text) ?? 'No answer' }}
                <span class="muted">| {{ $answer->earned_points }}/{{ $answer->max_points }} pts</span>
            </div>
        @endforeach
        @if ($submission->quizAnswers->count() > 3)
            <span class="muted">+ {{ $submission->quizAnswers->count() - 3 }} more answer{{ $submission->quizAnswers->count() - 3 === 1 ? '' : 's' }}</span>
        @endif
    </div>
@elseif ($submission->answer_text)
    <div class="submission-copy">{{ $submission->answer_text }}</div>
@endif

@if ($submission->file_path)
    <a class="btn btn-soft mt-8" href="{{ route('course-item-submissions.download', $submission) }}">Download File</a>
@endif

@if (
    ($submission->submission_type !== \App\Models\CourseSessionItem::TYPE_QUIZ || $submission->quizAnswers->isEmpty())
    && ! $submission->answer_text
    && ! $submission->file_path
)
    -
@endif
