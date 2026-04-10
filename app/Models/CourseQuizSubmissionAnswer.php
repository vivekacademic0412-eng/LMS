<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseQuizSubmissionAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_item_submission_id',
        'course_quiz_question_id',
        'answer_text',
        'is_correct',
        'earned_points',
        'max_points',
    ];

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
            'earned_points' => 'integer',
            'max_points' => 'integer',
        ];
    }

    public function submission(): BelongsTo
    {
        return $this->belongsTo(CourseItemSubmission::class, 'course_item_submission_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(CourseQuizQuestion::class, 'course_quiz_question_id');
    }
}
