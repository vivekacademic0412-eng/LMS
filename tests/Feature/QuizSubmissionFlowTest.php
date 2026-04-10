<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseEnrollment;
use App\Models\CourseItemSubmission;
use App\Models\CourseProgress;
use App\Models\CourseQuizQuestion;
use App\Models\CourseSession;
use App\Models\CourseSessionItem;
use App\Models\CourseWeek;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class QuizSubmissionFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_quiz_submission_is_auto_graded_and_marks_progress_when_passed(): void
    {
        [$student, $quizItem, $questionOne, $questionTwo, $questionThree, $enrollment] = $this->seedQuizCourse();

        $response = $this->actingAs($student)->post(route('course-session-items.submit', $quizItem), [
            'quiz_answers' => [
                $questionOne->id => '2',
                $questionTwo->id => 'true',
                $questionThree->id => 'learning',
            ],
        ]);

        $response->assertSessionHas('success');

        $submission = CourseItemSubmission::with('quizAnswers')->firstOrFail();

        $this->assertSame(CourseItemSubmission::STATUS_REVIEWED, $submission->review_status);
        $this->assertTrue((bool) $submission->passed);
        $this->assertSame(5, (int) $submission->score_earned);
        $this->assertSame(5, (int) $submission->score_total);
        $this->assertSame(100, (int) $submission->score_percent);
        $this->assertSame(1, (int) $submission->attempt_number);
        $this->assertCount(3, $submission->quizAnswers);

        $progress = CourseProgress::where('course_enrollment_id', $enrollment->id)
            ->where('course_session_item_id', $quizItem->id)
            ->first();

        $this->assertNotNull($progress?->completed_at);
    }

    public function test_student_cannot_exceed_the_quiz_attempt_limit(): void
    {
        [$student, $quizItem, $questionOne, $questionTwo, $questionThree] = $this->seedQuizCourse(maxAttempts: 1);

        $firstAttempt = $this->actingAs($student)->post(route('course-session-items.submit', $quizItem), [
            'quiz_answers' => [
                $questionOne->id => '1',
                $questionTwo->id => 'false',
                $questionThree->id => 'wrong',
            ],
        ]);

        $firstAttempt->assertSessionHas('success');
        $this->assertDatabaseCount('course_item_submissions', 1);

        $secondAttempt = $this->actingAs($student)->post(route('course-session-items.submit', $quizItem), [
            'quiz_answers' => [
                $questionOne->id => '2',
                $questionTwo->id => 'true',
                $questionThree->id => 'learning',
            ],
        ]);

        $secondAttempt->assertSessionHasErrors('quiz');
        $this->assertDatabaseCount('course_item_submissions', 1);
    }

    /**
     * @return array{0: User, 1: CourseSessionItem, 2: CourseQuizQuestion, 3: CourseQuizQuestion, 4: CourseQuizQuestion, 5: CourseEnrollment}
     */
    private function seedQuizCourse(int $maxAttempts = 3): array
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
        ]);
        $trainer = User::factory()->create([
            'role' => User::ROLE_TRAINER,
            'is_active' => true,
        ]);
        $student = User::factory()->create([
            'role' => User::ROLE_STUDENT,
            'is_active' => true,
        ]);

        $category = CourseCategory::create([
            'name' => 'Quiz Category',
            'slug' => 'quiz-category-'.Str::lower(Str::random(6)),
        ]);

        $course = Course::create([
            'category_id' => $category->id,
            'title' => 'Quiz Course '.Str::random(4),
            'slug' => 'quiz-course-'.Str::lower(Str::random(6)),
            'description' => 'Quiz testing course',
            'duration_hours' => 3,
            'created_by' => $admin->id,
        ]);

        $week = CourseWeek::create([
            'course_id' => $course->id,
            'week_number' => 1,
            'title' => 'Week 1',
        ]);

        $session = CourseSession::create([
            'course_week_id' => $week->id,
            'session_number' => 1,
            'title' => 'Session 1',
        ]);

        $quizItem = CourseSessionItem::create([
            'course_session_id' => $session->id,
            'item_type' => CourseSessionItem::TYPE_QUIZ,
            'title' => 'Knowledge Check',
            'content' => 'Answer the quiz below.',
            'is_live' => true,
            'live_at' => now(),
            'quiz_pass_percentage' => 70,
            'quiz_max_attempts' => $maxAttempts,
        ]);

        $questionOne = CourseQuizQuestion::create([
            'course_session_item_id' => $quizItem->id,
            'question_type' => CourseQuizQuestion::TYPE_SINGLE_CHOICE,
            'prompt' => 'Which option is correct?',
            'options' => ['Wrong', 'Correct', 'Another', 'Last'],
            'correct_answer' => '2',
            'points' => 2,
            'position' => 1,
        ]);

        $questionTwo = CourseQuizQuestion::create([
            'course_session_item_id' => $quizItem->id,
            'question_type' => CourseQuizQuestion::TYPE_TRUE_FALSE,
            'prompt' => 'True or false?',
            'correct_answer' => 'true',
            'points' => 1,
            'position' => 2,
        ]);

        $questionThree = CourseQuizQuestion::create([
            'course_session_item_id' => $quizItem->id,
            'question_type' => CourseQuizQuestion::TYPE_SHORT_ANSWER,
            'prompt' => 'Type learning',
            'accepted_answers' => ['learning'],
            'points' => 2,
            'position' => 3,
        ]);

        $enrollment = CourseEnrollment::create([
            'course_id' => $course->id,
            'student_id' => $student->id,
            'trainer_id' => $trainer->id,
            'assigned_by' => $admin->id,
        ]);

        return [$student, $quizItem, $questionOne, $questionTwo, $questionThree, $enrollment];
    }
}
