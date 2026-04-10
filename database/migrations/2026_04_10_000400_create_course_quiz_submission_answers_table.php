<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('course_quiz_submission_answers')) {
            return;
        }

        Schema::create('course_quiz_submission_answers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_item_submission_id')->constrained('course_item_submissions')->cascadeOnDelete();
            $table->foreignId('course_quiz_question_id')->constrained('course_quiz_questions')->cascadeOnDelete();
            $table->text('answer_text')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->unsignedSmallInteger('earned_points')->default(0);
            $table->unsignedSmallInteger('max_points')->default(0);
            $table->timestamps();

            $table->unique(
                ['course_item_submission_id', 'course_quiz_question_id'],
                'course_quiz_submission_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_quiz_submission_answers');
    }
};
