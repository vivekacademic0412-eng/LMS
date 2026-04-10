<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addIndexIfMissing('course_enrollments', ['course_id', 'student_id'], 'course_enrollments_course_student_idx');
        $this->addIndexIfMissing('course_enrollments', ['trainer_id'], 'course_enrollments_trainer_idx');
        $this->addIndexIfMissing('course_progress', ['course_enrollment_id'], 'course_progress_enrollment_idx');
        $this->addIndexIfMissing('course_progress', ['course_session_item_id'], 'course_progress_item_idx');
        $this->addIndexIfMissing('course_item_submissions', ['course_enrollment_id', 'course_session_item_id'], 'course_item_submissions_enrollment_item_idx');
        $this->addIndexIfMissing('course_item_submissions', ['review_status'], 'course_item_submissions_review_status_idx');
        $this->addIndexIfMissing('course_item_submissions', ['submitted_at'], 'course_item_submissions_submitted_at_idx');
        $this->addIndexIfMissing('course_quiz_questions', ['course_session_item_id'], 'course_quiz_questions_item_idx');
        $this->addIndexIfMissing('course_quiz_submission_answers', ['course_item_submission_id'], 'course_quiz_answers_submission_idx');
        $this->addIndexIfMissing('course_quiz_submission_answers', ['course_quiz_question_id'], 'course_quiz_answers_question_idx');
        $this->addIndexIfMissing('activity_logs', ['created_at'], 'activity_logs_created_at_idx');
    }

    public function down(): void
    {
        $this->dropIndexIfExists('course_enrollments', 'course_enrollments_course_student_idx');
        $this->dropIndexIfExists('course_enrollments', 'course_enrollments_trainer_idx');
        $this->dropIndexIfExists('course_progress', 'course_progress_enrollment_idx');
        $this->dropIndexIfExists('course_progress', 'course_progress_item_idx');
        $this->dropIndexIfExists('course_item_submissions', 'course_item_submissions_enrollment_item_idx');
        $this->dropIndexIfExists('course_item_submissions', 'course_item_submissions_review_status_idx');
        $this->dropIndexIfExists('course_item_submissions', 'course_item_submissions_submitted_at_idx');
        $this->dropIndexIfExists('course_quiz_questions', 'course_quiz_questions_item_idx');
        $this->dropIndexIfExists('course_quiz_submission_answers', 'course_quiz_answers_submission_idx');
        $this->dropIndexIfExists('course_quiz_submission_answers', 'course_quiz_answers_question_idx');
        $this->dropIndexIfExists('activity_logs', 'activity_logs_created_at_idx');
    }

    private function addIndexIfMissing(string $table, array $columns, string $indexName): void
    {
        if (! Schema::hasTable($table) || $this->indexExists($table, $indexName)) {
            return;
        }

        Schema::table($table, function (Blueprint $table) use ($columns, $indexName): void {
            $table->index($columns, $indexName);
        });
    }

    private function dropIndexIfExists(string $table, string $indexName): void
    {
        if (! Schema::hasTable($table) || ! $this->indexExists($table, $indexName)) {
            return;
        }

        Schema::table($table, function (Blueprint $table) use ($indexName): void {
            $table->dropIndex($indexName);
        });
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $database = DB::getDatabaseName();

        $result = DB::select(
            'SELECT COUNT(1) as count FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ?',
            [$database, $table, $indexName]
        );

        return (int) ($result[0]->count ?? 0) > 0;
    }
};
