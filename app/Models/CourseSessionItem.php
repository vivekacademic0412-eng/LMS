<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseSessionItem extends Model
{
    use HasFactory;

    public const TYPE_INTRO = 'intro';
    public const TYPE_MAIN_VIDEO = 'main_video';
    public const TYPE_TASK = 'task';
    public const TYPE_QUIZ = 'quiz';

    public const TYPES = [
        self::TYPE_INTRO,
        self::TYPE_MAIN_VIDEO,
        self::TYPE_TASK,
        self::TYPE_QUIZ,
    ];

    protected $fillable = [
        'course_session_id',
        'item_type',
        'title',
        'resource_type',
        'content',
        'resource_url',
        'estimated_minutes',
        'is_live',
        'live_at',
        'quiz_pass_percentage',
        'quiz_max_attempts',
        'quiz_time_limit_minutes',
        'cloudinary_public_id',
        'cloudinary_resource_type',
        'cloudinary_format',
        'cloudinary_delivery_type',
    ];

    protected function casts(): array
    {
        return [
            'estimated_minutes' => 'integer',
            'is_live' => 'boolean',
            'live_at' => 'datetime',
            'quiz_pass_percentage' => 'integer',
            'quiz_max_attempts' => 'integer',
            'quiz_time_limit_minutes' => 'integer',
        ];
    }

    public function hasPrivateCloudinaryAsset(): bool
    {
        return (bool) $this->cloudinary_public_id
            && (bool) $this->cloudinary_resource_type
            && (bool) $this->cloudinary_format;
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(CourseSession::class, 'course_session_id');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(CourseProgress::class, 'course_session_item_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(CourseItemSubmission::class, 'course_session_item_id');
    }

    public function quizQuestions(): HasMany
    {
        return $this->hasMany(CourseQuizQuestion::class, 'course_session_item_id')
            ->orderBy('position')
            ->orderBy('id');
    }

    public function quizPassPercentage(): int
    {
        return max(1, min(100, (int) ($this->quiz_pass_percentage ?? 70)));
    }

    public function quizMaxAttempts(): int
    {
        return max(1, (int) ($this->quiz_max_attempts ?? 3));
    }

    public function quizTimeLimitMinutes(): ?int
    {
        $minutes = (int) ($this->quiz_time_limit_minutes ?? 0);

        return $minutes > 0 ? $minutes : null;
    }

    public function estimatedMinutes(): ?int
    {
        $minutes = $this->item_type === self::TYPE_QUIZ && $this->quizTimeLimitMinutes() !== null
            ? $this->quizTimeLimitMinutes()
            : (int) ($this->estimated_minutes ?? 0);

        return $minutes > 0 ? $minutes : null;
    }
}
