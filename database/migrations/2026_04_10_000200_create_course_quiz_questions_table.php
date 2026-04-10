<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('course_quiz_questions')) {
            return;
        }

        Schema::create('course_quiz_questions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_session_item_id')->constrained('course_session_items')->cascadeOnDelete();
            $table->string('question_type', 30);
            $table->text('prompt');
            $table->json('options')->nullable();
            $table->string('correct_answer', 255)->nullable();
            $table->json('accepted_answers')->nullable();
            $table->unsignedSmallInteger('points')->default(1);
            $table->unsignedInteger('position')->default(1);
            $table->text('explanation')->nullable();
            $table->timestamps();

            $table->index(['course_session_item_id', 'position'], 'quiz_questions_item_position_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_quiz_questions');
    }
};
