<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('course_session_items') || Schema::hasColumn('course_session_items', 'quiz_pass_percentage')) {
            return;
        }

        Schema::table('course_session_items', function (Blueprint $table): void {
            $table->unsignedTinyInteger('quiz_pass_percentage')->default(70)->after('live_at');
            $table->unsignedSmallInteger('quiz_max_attempts')->default(3)->after('quiz_pass_percentage');
            $table->unsignedSmallInteger('quiz_time_limit_minutes')->nullable()->after('quiz_max_attempts');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('course_session_items') || ! Schema::hasColumn('course_session_items', 'quiz_pass_percentage')) {
            return;
        }

        Schema::table('course_session_items', function (Blueprint $table): void {
            $table->dropColumn([
                'quiz_pass_percentage',
                'quiz_max_attempts',
                'quiz_time_limit_minutes',
            ]);
        });
    }
};
