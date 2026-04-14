<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('course_session_items') || Schema::hasColumn('course_session_items', 'estimated_minutes')) {
            return;
        }

        Schema::table('course_session_items', function (Blueprint $table): void {
            $table->unsignedSmallInteger('estimated_minutes')->nullable()->after('resource_url');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('course_session_items') || ! Schema::hasColumn('course_session_items', 'estimated_minutes')) {
            return;
        }

        Schema::table('course_session_items', function (Blueprint $table): void {
            $table->dropColumn('estimated_minutes');
        });
    }
};
