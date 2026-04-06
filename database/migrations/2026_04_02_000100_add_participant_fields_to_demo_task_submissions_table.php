<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demo_task_submissions', function (Blueprint $table): void {
            $table->string('participant_name')->nullable()->after('demo_task_assignment_id');
            $table->string('participant_email')->nullable()->after('participant_name');
            $table->string('participant_phone', 40)->nullable()->after('participant_email');
        });
    }

    public function down(): void
    {
        Schema::table('demo_task_submissions', function (Blueprint $table): void {
            $table->dropColumn([
                'participant_name',
                'participant_email',
                'participant_phone',
            ]);
        });
    }
};
