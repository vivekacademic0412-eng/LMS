<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('demo_feature_videos')) {
            Schema::table('demo_feature_videos', function (Blueprint $table): void {
                if (! Schema::hasColumn('demo_feature_videos', 'video_ratio')) {
                    $table->string('video_ratio', 20)->default('landscape')->after('youtube_id');
                }
            });
        }

        if (Schema::hasTable('demo_review_videos')) {
            Schema::table('demo_review_videos', function (Blueprint $table): void {
                if (! Schema::hasColumn('demo_review_videos', 'video_ratio')) {
                    $table->string('video_ratio', 20)->default('landscape')->after('youtube_id');
                }
            });
        }

        if (Schema::hasTable('demo_tasks')) {
            Schema::table('demo_tasks', function (Blueprint $table): void {
                if (! Schema::hasColumn('demo_tasks', 'video_ratio')) {
                    $table->string('video_ratio', 20)->default('reel')->after('task_video_size');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('demo_tasks')) {
            Schema::table('demo_tasks', function (Blueprint $table): void {
                if (Schema::hasColumn('demo_tasks', 'video_ratio')) {
                    $table->dropColumn('video_ratio');
                }
            });
        }

        if (Schema::hasTable('demo_review_videos')) {
            Schema::table('demo_review_videos', function (Blueprint $table): void {
                if (Schema::hasColumn('demo_review_videos', 'video_ratio')) {
                    $table->dropColumn('video_ratio');
                }
            });
        }

        if (Schema::hasTable('demo_feature_videos')) {
            Schema::table('demo_feature_videos', function (Blueprint $table): void {
                if (Schema::hasColumn('demo_feature_videos', 'video_ratio')) {
                    $table->dropColumn('video_ratio');
                }
            });
        }
    }
};
