@php
    $latestQuizAnswers = $selectedSubmission?->quizAnswers ?? collect();
@endphp

<div class="submission-box">
    <strong>Take this quiz</strong>
    <p class="viewer-note">
        {{ $selectedQuizQuestions->count() }} question{{ $selectedQuizQuestions->count() === 1 ? '' : 's' }}
        | Pass mark {{ $selectedQuizPassPercentage }}%
        | Attempts used {{ $selectedQuizAttemptsUsed }}/{{ $selectedItem->quizMaxAttempts() }}
        @if ($selectedQuizTimeLimit)
            | Time limit {{ $selectedQuizTimeLimit }} min
        @endif
    </p>

    @if (! $selectedQuizConfigured)
        <p class="viewer-note">This quiz is not configured yet. Ask your trainer or admin to add quiz questions first.</p>
    @elseif (! $selectedItem->is_live)
        <p class="viewer-note">This quiz is not live yet. Your trainer must open it first.</p>
    @elseif ($selectedSubmission?->passed)
        <p class="viewer-note">You already passed this quiz. Your latest score is {{ $selectedSubmission->scoreLabel() }} ({{ $selectedSubmission->scorePercentLabel() }}).</p>
    @elseif (! $selectedQuizCanSubmit)
        <p class="viewer-note">You have used all attempts for this quiz. Please wait for trainer guidance before trying again.</p>
    @else
        <p class="viewer-note">Answer each question below and submit this attempt from the same workspace.</p>
        <form method="POST" action="{{ route('course-session-items.submit', $selectedItem) }}" class="stack">
            @csrf
            @foreach ($selectedQuizQuestions as $question)
                <div class="submission-answer">
                    <strong>Q{{ $loop->iteration }}. {{ $question->prompt }}</strong>
                    <div class="viewer-note">Worth {{ $question->points }} point{{ $question->points === 1 ? '' : 's' }}.</div>

                    @if ($question->question_type === \App\Models\CourseQuizQuestion::TYPE_SINGLE_CHOICE)
                        <div class="stack mt-8">
                            @foreach ($question->optionList() as $optionIndex => $optionLabel)
                                @php
                                    $optionValue = (string) ($optionIndex + 1);
                                @endphp
                                <label class="viewer-note">
                                    <input
                                        type="radio"
                                        name="quiz_answers[{{ $question->id }}]"
                                        value="{{ $optionValue }}"
                                        @checked(old('quiz_answers.'.$question->id) === $optionValue)
                                    >
                                    {{ $optionLabel }}
                                </label>
                            @endforeach
                        </div>
                    @elseif ($question->question_type === \App\Models\CourseQuizQuestion::TYPE_TRUE_FALSE)
                        <div class="stack mt-8">
                            @foreach (['true' => 'True', 'false' => 'False'] as $optionValue => $optionLabel)
                                <label class="viewer-note">
                                    <input
                                        type="radio"
                                        name="quiz_answers[{{ $question->id }}]"
                                        value="{{ $optionValue }}"
                                        @checked(old('quiz_answers.'.$question->id) === $optionValue)
                                    >
                                    {{ $optionLabel }}
                                </label>
                            @endforeach
                        </div>
                    @else
                        <textarea
                            name="quiz_answers[{{ $question->id }}]"
                            rows="3"
                            placeholder="Type your answer here..."
                            required
                        >{{ old('quiz_answers.'.$question->id) }}</textarea>
                    @endif
                </div>
            @endforeach

            <button class="course-action course-action--soft" type="submit">
                Submit Quiz Attempt {{ $selectedQuizAttemptsUsed + 1 }}
            </button>
        </form>
    @endif

    @if ($selectedSubmission)
        <div class="submission-meta">
            <span>Last attempt submitted {{ optional($selectedSubmission->submitted_at)->diffForHumans() }}</span>
            <span>Attempt {{ $selectedSubmission->attempt_number ?? 1 }}</span>
            <span>Review status: {{ $selectedSubmission->reviewStatusLabel() }}</span>
            @if ($selectedSubmission->hasScore())
                <span>Score: {{ $selectedSubmission->scoreLabel() }} ({{ $selectedSubmission->scorePercentLabel() }})</span>
                <span>Result: {{ $selectedSubmission->passStatusLabel() }}</span>
            @endif
            @if ($selectedSubmission->review_notes)
                <div class="submission-answer">{{ \Illuminate\Support\Str::limit($selectedSubmission->review_notes, 220) }}</div>
            @endif
        </div>

        @if ($latestQuizAnswers->isNotEmpty())
            <div class="stack mt-12">
                @foreach ($latestQuizAnswers as $answer)
                    <div class="submission-answer">
                        <strong>Q{{ $loop->iteration }}. {{ $answer->question?->prompt ?? 'Question' }}</strong>
                        <div>Your answer: {{ $answer->question?->displaySubmittedAnswer($answer->answer_text) ?? 'No answer' }}</div>
                        <div>Correct answer: {{ $answer->question?->correctAnswerDisplay() ?? '-' }}</div>
                        <div>Points: {{ $answer->earned_points }}/{{ $answer->max_points }}</div>
                        @if ($answer->question?->explanation)
                            <div>{{ \Illuminate\Support\Str::limit($answer->question->explanation, 180) }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    @endif
</div>
