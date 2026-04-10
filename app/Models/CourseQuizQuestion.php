<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseQuizQuestion extends Model
{
    use HasFactory;

    public const TYPE_SINGLE_CHOICE = 'single_choice';
    public const TYPE_TRUE_FALSE = 'true_false';
    public const TYPE_SHORT_ANSWER = 'short_answer';

    public const TYPES = [
        self::TYPE_SINGLE_CHOICE,
        self::TYPE_TRUE_FALSE,
        self::TYPE_SHORT_ANSWER,
    ];

    protected $fillable = [
        'course_session_item_id',
        'question_type',
        'prompt',
        'options',
        'correct_answer',
        'accepted_answers',
        'points',
        'position',
        'explanation',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'accepted_answers' => 'array',
            'points' => 'integer',
            'position' => 'integer',
        ];
    }

    public static function typeOptions(): array
    {
        return [
            self::TYPE_SINGLE_CHOICE => 'Single Choice',
            self::TYPE_TRUE_FALSE => 'True / False',
            self::TYPE_SHORT_ANSWER => 'Short Answer',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(CourseSessionItem::class, 'course_session_item_id');
    }

    public function submissionAnswers(): HasMany
    {
        return $this->hasMany(CourseQuizSubmissionAnswer::class, 'course_quiz_question_id');
    }

    /**
     * @return list<string>
     */
    public function optionList(): array
    {
        return collect($this->options ?? [])
            ->map(fn ($option) => trim((string) $option))
            ->filter(fn (string $option): bool => $option !== '')
            ->values()
            ->all();
    }

    /**
     * @return list<string>
     */
    public function acceptedAnswerList(): array
    {
        return collect($this->accepted_answers ?? [])
            ->map(fn ($answer) => trim((string) $answer))
            ->filter(fn (string $answer): bool => $answer !== '')
            ->values()
            ->all();
    }

    public function correctAnswerDisplay(): string
    {
        return match ($this->question_type) {
            self::TYPE_SINGLE_CHOICE => $this->displaySubmittedAnswer($this->correct_answer) ?: 'Not set',
            self::TYPE_TRUE_FALSE => $this->displaySubmittedAnswer($this->correct_answer) ?: 'Not set',
            self::TYPE_SHORT_ANSWER => implode(', ', $this->acceptedAnswerList()),
            default => 'Not set',
        };
    }

    public function displaySubmittedAnswer(mixed $answer): string
    {
        $answer = trim((string) $answer);

        if ($answer === '') {
            return 'No answer';
        }

        return match ($this->question_type) {
            self::TYPE_SINGLE_CHOICE => $this->optionLabelForIndex($answer),
            self::TYPE_TRUE_FALSE => match ($this->normalizeAnswer($answer)) {
                'true' => 'True',
                'false' => 'False',
                default => $answer,
            },
            default => $answer,
        };
    }

    public function answerIsCorrect(mixed $answer): bool
    {
        $normalizedAnswer = $this->normalizeAnswer($answer);

        if ($normalizedAnswer === '') {
            return false;
        }

        return match ($this->question_type) {
            self::TYPE_SINGLE_CHOICE => $normalizedAnswer === $this->normalizeAnswer($this->correct_answer),
            self::TYPE_TRUE_FALSE => $normalizedAnswer === $this->normalizeAnswer($this->correct_answer),
            self::TYPE_SHORT_ANSWER => collect($this->acceptedAnswerList())
                ->map(fn (string $accepted): string => $this->normalizeAnswer($accepted))
                ->contains($normalizedAnswer),
            default => false,
        };
    }

    public function normalizeAnswer(mixed $answer): string
    {
        $value = trim((string) $answer);

        return mb_strtolower(preg_replace('/\s+/', ' ', $value) ?? '');
    }

    private function optionLabelForIndex(string $answer): string
    {
        $index = max(1, (int) $answer) - 1;
        $options = $this->optionList();

        return $options[$index] ?? $answer;
    }
}
