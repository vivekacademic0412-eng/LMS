@extends('layouts.app')

@section('content')
    @php
        $questionRows = old('questions', $questionRows);

        if (! is_array($questionRows) || $questionRows === []) {
            $questionRows = [[
                'id' => null,
                'prompt' => '',
                'question_type' => \App\Models\CourseQuizQuestion::TYPE_SINGLE_CHOICE,
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
            ]];
        }
    @endphp

    <style>
        .quiz-editor-shell { display: grid; gap: 18px; }
        .quiz-editor-head {
            display: flex;
            justify-content: space-between;
            align-items: start;
            gap: 16px;
        }
        .quiz-editor-head h1,
        .quiz-card h3 { margin: 0; }
        .quiz-editor-meta,
        .question-meta,
        .helper-note,
        .quiz-question-panels,
        .quiz-question-list,
        .quiz-settings-grid { display: grid; gap: 12px; }
        .quiz-editor-links,
        .question-actions,
        .question-toolbar { display: flex; flex-wrap: wrap; gap: 8px; }
        .quiz-card {
            border: 1px solid var(--line);
            border-radius: 16px;
            background: var(--card);
            box-shadow: var(--shadow);
            padding: 18px;
        }
        .quiz-settings-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
        .question-card {
            border: 1px solid #d8e4f3;
            border-radius: 16px;
            padding: 16px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        }
        .question-card.is-removed {
            opacity: 0.58;
            border-style: dashed;
            background: #f8f9fb;
        }
        .question-grid {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        }
        .question-grid .field textarea,
        .question-grid .field input,
        .question-grid .field select { width: 100%; }
        .question-panel {
            border: 1px solid #d8e4f3;
            border-radius: 12px;
            padding: 14px;
            background: #fff;
        }
        .question-panel[hidden] { display: none; }
        .question-order {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            border-radius: 999px;
            background: #eaf2ff;
            color: #1f4fa3;
            font-size: 12px;
            font-weight: 800;
        }
        .helper-note {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }
        .removed-note {
            color: #9a4c19;
            font-size: 12px;
            font-weight: 700;
        }
        .option-grid {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        }
    </style>

    <div class="quiz-editor-shell">
        <section class="card">
            <div class="quiz-editor-head">
                <div class="quiz-editor-meta">
                    <div class="muted">Quiz Builder</div>
                    <h1>{{ $item->title }}</h1>
                    <p class="helper-note">
                        {{ $course?->title ?? 'Course' }}
                        @if ($item->session?->week)
                            | Week {{ $item->session->week->week_number }}
                        @endif
                        @if ($item->session)
                            | Session {{ $item->session->session_number }}
                        @endif
                    </p>
                </div>
                <div class="quiz-editor-links">
                    <a href="{{ route('courses.show', $course) }}" class="btn btn-soft">Back to Course</a>
                </div>
            </div>
        </section>

        <form method="POST" action="{{ route('course-session-items.quiz.update', $item) }}" class="stack">
            @csrf
            @method('PUT')

            <section class="quiz-card">
                <h3>Quiz Settings</h3>
                <p class="helper-note">Set the pass mark, attempt cap, and quiz notes students will see before answering.</p>

                <div class="quiz-settings-grid mt-12">
                    <div class="field">
                        <label>Quiz Title</label>
                        <input type="text" name="title" value="{{ old('title', $item->title) }}" required>
                    </div>
                    <div class="field">
                        <label>Pass Percentage</label>
                        <input type="number" min="1" max="100" name="quiz_pass_percentage" value="{{ old('quiz_pass_percentage', $item->quizPassPercentage()) }}" required>
                    </div>
                    <div class="field">
                        <label>Max Attempts</label>
                        <input type="number" min="1" max="50" name="quiz_max_attempts" value="{{ old('quiz_max_attempts', $item->quizMaxAttempts()) }}" required>
                    </div>
                    <div class="field">
                        <label>Time Limit (Minutes)</label>
                        <input type="number" min="1" max="600" name="quiz_time_limit_minutes" value="{{ old('quiz_time_limit_minutes', $item->quizTimeLimitMinutes()) }}" placeholder="Optional">
                    </div>
                </div>

                <div class="field mt-12">
                    <label>Student Instructions</label>
                    <textarea name="content" rows="4" placeholder="Explain what learners should remember before they answer.">{{ old('content', $item->content) }}</textarea>
                </div>
            </section>

            <section class="quiz-card">
                <div class="quiz-editor-head">
                    <div>
                        <h3>Questions</h3>
                        <p class="helper-note">Use single choice, true/false, and short-answer questions. Short answers are auto-graded against accepted answers.</p>
                    </div>
                    <div class="question-actions">
                        <button type="button" class="btn btn-soft" data-add-question>Add Question</button>
                    </div>
                </div>

                <div class="quiz-question-list mt-12" data-question-list data-next-index="{{ count($questionRows) }}">
                    @foreach ($questionRows as $index => $question)
                        @php
                            $questionType = (string) ($question['question_type'] ?? \App\Models\CourseQuizQuestion::TYPE_SINGLE_CHOICE);
                            $isRemoved = !empty($question['remove']);
                            $questionId = $question['id'] ?? null;
                        @endphp
                        <article
                            class="question-card {{ $isRemoved ? 'is-removed' : '' }}"
                            data-question-card
                            data-persisted="{{ $questionId ? '1' : '0' }}"
                        >
                            <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $questionId }}">
                            <input type="hidden" name="questions[{{ $index }}][remove]" value="{{ $isRemoved ? '1' : '0' }}" data-remove-input>

                            <div class="question-toolbar">
                                <span class="question-order">Q{{ $index + 1 }}</span>
                                <span class="removed-note" data-removed-label @if (! $isRemoved) hidden @endif>Marked for removal</span>
                            </div>

                            <div class="question-grid mt-12">
                                <div class="field" style="grid-column: 1 / -1;">
                                    <label>Prompt</label>
                                    <textarea name="questions[{{ $index }}][prompt]" rows="3" placeholder="Write the question prompt here.">{{ $question['prompt'] ?? '' }}</textarea>
                                </div>
                                <div class="field">
                                    <label>Question Type</label>
                                    <select name="questions[{{ $index }}][question_type]" data-question-type>
                                        @foreach ($questionTypeOptions as $value => $label)
                                            <option value="{{ $value }}" @selected($questionType === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field">
                                    <label>Points</label>
                                    <input type="number" min="1" max="100" name="questions[{{ $index }}][points]" value="{{ $question['points'] ?? 1 }}">
                                </div>
                                <div class="field" style="grid-column: 1 / -1;">
                                    <label>Explanation / Feedback</label>
                                    <textarea name="questions[{{ $index }}][explanation]" rows="2" placeholder="Optional feedback shown after grading or review.">{{ $question['explanation'] ?? '' }}</textarea>
                                </div>
                            </div>

                            <div class="quiz-question-panels mt-12">
                                <div class="question-panel" data-type-panel="single_choice" @if ($questionType !== \App\Models\CourseQuizQuestion::TYPE_SINGLE_CHOICE) hidden @endif>
                                    <div class="option-grid">
                                        <div class="field">
                                            <label>Option 1</label>
                                            <input type="text" name="questions[{{ $index }}][option_1]" value="{{ $question['option_1'] ?? '' }}">
                                        </div>
                                        <div class="field">
                                            <label>Option 2</label>
                                            <input type="text" name="questions[{{ $index }}][option_2]" value="{{ $question['option_2'] ?? '' }}">
                                        </div>
                                        <div class="field">
                                            <label>Option 3</label>
                                            <input type="text" name="questions[{{ $index }}][option_3]" value="{{ $question['option_3'] ?? '' }}">
                                        </div>
                                        <div class="field">
                                            <label>Option 4</label>
                                            <input type="text" name="questions[{{ $index }}][option_4]" value="{{ $question['option_4'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="field mt-12">
                                        <label>Correct Option</label>
                                        <select name="questions[{{ $index }}][correct_option]">
                                            @foreach ([1, 2, 3, 4] as $optionNumber)
                                                <option value="{{ $optionNumber }}" @selected((int) ($question['correct_option'] ?? 1) === $optionNumber)>Option {{ $optionNumber }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="question-panel" data-type-panel="true_false" @if ($questionType !== \App\Models\CourseQuizQuestion::TYPE_TRUE_FALSE) hidden @endif>
                                    <div class="field">
                                        <label>Correct Answer</label>
                                        <select name="questions[{{ $index }}][correct_true_false]">
                                            <option value="true" @selected(($question['correct_true_false'] ?? 'true') === 'true')>True</option>
                                            <option value="false" @selected(($question['correct_true_false'] ?? 'true') === 'false')>False</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="question-panel" data-type-panel="short_answer" @if ($questionType !== \App\Models\CourseQuizQuestion::TYPE_SHORT_ANSWER) hidden @endif>
                                    <div class="field">
                                        <label>Accepted Answers</label>
                                        <textarea name="questions[{{ $index }}][accepted_answers]" rows="4" placeholder="One accepted answer per line.">{{ $question['accepted_answers'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="question-actions mt-12">
                                <button type="button" class="btn btn-soft" data-question-remove>Remove</button>
                                <button type="button" class="btn btn-soft" data-question-restore @if (! $isRemoved) hidden @endif>Restore</button>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <div class="actions-row">
                <button class="btn" type="submit">Save Quiz</button>
                <a href="{{ route('courses.show', $course) }}" class="btn btn-soft">Cancel</a>
            </div>
        </form>
    </div>

    <template id="quiz-question-template">
        <article class="question-card" data-question-card data-persisted="0">
            <input type="hidden" name="questions[__INDEX__][id]" value="">
            <input type="hidden" name="questions[__INDEX__][remove]" value="0" data-remove-input>

            <div class="question-toolbar">
                <span class="question-order">QNew</span>
                <span class="removed-note" data-removed-label hidden>Marked for removal</span>
            </div>

            <div class="question-grid mt-12">
                <div class="field" style="grid-column: 1 / -1;">
                    <label>Prompt</label>
                    <textarea name="questions[__INDEX__][prompt]" rows="3" placeholder="Write the question prompt here."></textarea>
                </div>
                <div class="field">
                    <label>Question Type</label>
                    <select name="questions[__INDEX__][question_type]" data-question-type>
                        @foreach ($questionTypeOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Points</label>
                    <input type="number" min="1" max="100" name="questions[__INDEX__][points]" value="1">
                </div>
                <div class="field" style="grid-column: 1 / -1;">
                    <label>Explanation / Feedback</label>
                    <textarea name="questions[__INDEX__][explanation]" rows="2" placeholder="Optional feedback shown after grading or review."></textarea>
                </div>
            </div>

            <div class="quiz-question-panels mt-12">
                <div class="question-panel" data-type-panel="single_choice">
                    <div class="option-grid">
                        <div class="field">
                            <label>Option 1</label>
                            <input type="text" name="questions[__INDEX__][option_1]" value="">
                        </div>
                        <div class="field">
                            <label>Option 2</label>
                            <input type="text" name="questions[__INDEX__][option_2]" value="">
                        </div>
                        <div class="field">
                            <label>Option 3</label>
                            <input type="text" name="questions[__INDEX__][option_3]" value="">
                        </div>
                        <div class="field">
                            <label>Option 4</label>
                            <input type="text" name="questions[__INDEX__][option_4]" value="">
                        </div>
                    </div>
                    <div class="field mt-12">
                        <label>Correct Option</label>
                        <select name="questions[__INDEX__][correct_option]">
                            @foreach ([1, 2, 3, 4] as $optionNumber)
                                <option value="{{ $optionNumber }}">Option {{ $optionNumber }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="question-panel" data-type-panel="true_false" hidden>
                    <div class="field">
                        <label>Correct Answer</label>
                        <select name="questions[__INDEX__][correct_true_false]">
                            <option value="true">True</option>
                            <option value="false">False</option>
                        </select>
                    </div>
                </div>

                <div class="question-panel" data-type-panel="short_answer" hidden>
                    <div class="field">
                        <label>Accepted Answers</label>
                        <textarea name="questions[__INDEX__][accepted_answers]" rows="4" placeholder="One accepted answer per line."></textarea>
                    </div>
                </div>
            </div>

            <div class="question-actions mt-12">
                <button type="button" class="btn btn-soft" data-question-remove>Remove</button>
                <button type="button" class="btn btn-soft" data-question-restore hidden>Restore</button>
            </div>
        </article>
    </template>

    <script src="{{ asset('js/quiz-editor.js') }}" defer></script>
@endsection
