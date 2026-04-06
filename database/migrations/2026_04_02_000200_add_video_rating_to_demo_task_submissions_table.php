<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demo_task_submissions', function (Blueprint $table): void {
            $table->unsignedTinyInteger('video_rating')->nullable()->after('participant_phone');
        });
    }

    public function down(): void
    {
        Schema::table('demo_task_submissions', function (Blueprint $table): void {
            $table->dropColumn('video_rating');
        });
    }
};

