<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('course_item_submissions') || Schema::hasColumn('course_item_submissions', 'score_earned')) {
            return;
        }

        Schema::table('course_item_submissions', function (Blueprint $table): void {
            $table->unsignedInteger('score_earned')->nullable()->after('answer_text');
            $table->unsignedInteger('score_total')->nullable()->after('score_earned');
            $table->unsignedTinyInteger('score_percent')->nullable()->after('score_total');
            $table->boolean('passed')->nullable()->after('score_percent');
            $table->unsignedSmallInteger('attempt_number')->default(1)->after('passed');
            $table->index(['submission_type', 'passed'], 'course_item_submissions_type_passed_idx');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('course_item_submissions') || ! Schema::hasColumn('course_item_submissions', 'score_earned')) {
            return;
        }

        Schema::table('course_item_submissions', function (Blueprint $table): void {
            $table->dropIndex('course_item_submissions_type_passed_idx');
            $table->dropColumn([
                'score_earned',
                'score_total',
                'score_percent',
                'passed',
                'attempt_number',
            ]);
        });
    }
};
