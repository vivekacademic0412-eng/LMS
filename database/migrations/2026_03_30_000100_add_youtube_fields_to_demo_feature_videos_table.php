<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demo_feature_videos', function (Blueprint $table): void {
            if (! Schema::hasColumn('demo_feature_videos', 'youtube_url')) {
                $table->string('youtube_url', 500)->nullable()->after('file_size');
            }

            if (! Schema::hasColumn('demo_feature_videos', 'youtube_id')) {
                $table->string('youtube_id', 32)->nullable()->after('youtube_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('demo_feature_videos', function (Blueprint $table): void {
            if (Schema::hasColumn('demo_feature_videos', 'youtube_id')) {
                $table->dropColumn('youtube_id');
            }

            if (Schema::hasColumn('demo_feature_videos', 'youtube_url')) {
                $table->dropColumn('youtube_url');
            }
        });
    }
};
