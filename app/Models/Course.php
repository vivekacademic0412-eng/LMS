<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'subcategory_id',
        'title',
        'short_description',
        'slug',
        'description',
        'language',
        'thumbnail',
        'duration_hours',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'duration_hours' => 'integer',
        ];
    }

    public function scopeWithEstimatedMinutesTotal(Builder $query): Builder
    {
        return $query->addSelect([
            'estimated_minutes_total' => CourseSessionItem::query()
                ->selectRaw(
                    'COALESCE(SUM(CASE WHEN course_session_items.item_type = ? AND course_session_items.quiz_time_limit_minutes IS NOT NULL THEN course_session_items.quiz_time_limit_minutes ELSE COALESCE(course_session_items.estimated_minutes, 0) END), 0)',
                    [CourseSessionItem::TYPE_QUIZ]
                )
                ->join('course_sessions', 'course_sessions.id', '=', 'course_session_items.course_session_id')
                ->join('course_weeks', 'course_weeks.id', '=', 'course_sessions.course_week_id')
                ->whereColumn('course_weeks.course_id', 'courses.id'),
        ]);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'subcategory_id');
    }

    public function days(): HasMany
    {
        return $this->hasMany(CourseDay::class, 'course_id')->orderBy('day_number');
    }

    public function weeks(): HasMany
    {
        return $this->hasMany(CourseWeek::class, 'course_id')->orderBy('week_number');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class, 'course_id');
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (! $this->thumbnail) {
            return null;
        }

        if (Str::startsWith($this->thumbnail, ['http://', 'https://'])) {
            return $this->thumbnail;
        }

        return '/storage/'.ltrim($this->thumbnail, '/');
    }

    public function estimatedItemMinutesTotal(): int
    {
        $attributeTotal = $this->getAttribute('estimated_minutes_total');

        if ($attributeTotal !== null) {
            return max(0, (int) $attributeTotal);
        }

        if (! $this->relationLoaded('weeks')) {
            return 0;
        }

        return (int) $this->weeks
            ->flatMap->sessions
            ->flatMap->items
            ->sum(fn ($item): int => (int) ($item?->estimatedMinutes() ?? 0));
    }

    public function estimatedDurationMinutes(): int
    {
        $itemMinutesTotal = $this->estimatedItemMinutesTotal();

        if ($itemMinutesTotal > 0) {
            return $itemMinutesTotal;
        }

        return max(1, (int) ($this->duration_hours ?? 1)) * 60;
    }

    public function estimatedDurationLabel(): string
    {
        $minutes = $this->estimatedDurationMinutes();
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        if ($hours > 0 && $remainingMinutes > 0) {
            return $hours.'h '.$remainingMinutes.'m';
        }

        if ($hours > 0) {
            return $hours.'h';
        }

        return $remainingMinutes.' min';
    }
}
