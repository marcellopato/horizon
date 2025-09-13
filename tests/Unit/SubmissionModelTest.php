<?php

namespace Tests\Unit;

use App\Models\Interview;
use App\Models\Question;
use App\Models\Submission;
use App\Models\SubmissionAnswer;
use App\Models\User;
use Tests\TestCase;

/**
 * Unit tests for the Submission model helpers.
 *
 * @category Tests
 * @package  Unit
 * @author   Horizon Team <dev@horizon.local>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/marcellopato/horizon
 */
class SubmissionModelTest extends TestCase
{
    /**
     * It should be considered completed when status is
     * completed, submitted, or reviewed.
     *
     * @return void
     */
    public function testIsCompletedTrueForCompletedSubmittedReviewed(): void
    {
        $submission = new Submission(['status' => 'completed']);
        $this->assertTrue($submission->isCompleted());

        $submission->status = 'submitted';
        $this->assertTrue($submission->isCompleted());

        $submission->status = 'reviewed';
        $this->assertTrue($submission->isCompleted());
    }

    /**
     * It should not be considered completed for in_progress
     * or other statuses.
     *
     * @return void
     */
    public function testIsCompletedFalseForInProgressAndOther(): void
    {
        $submission = new Submission(['status' => 'in_progress']);
        $this->assertFalse($submission->isCompleted());

        $submission->status = 'draft';
        $this->assertFalse($submission->isCompleted());
    }

    /**
     * It should calculate progress percentage based on
     * completed answers over total questions.
     *
     * @return void
     */
    public function testGetProgressPercentageCalculatesCorrectly(): void
    {
        $user = User::factory()->create();

        $interview = Interview::create(
            [
                'title' => 'Tech Interview',
                'description' => 'Test',
                'is_active' => true,
                'time_limit' => 30,
                'created_by' => $user->id,
            ]
        );

        // Create 4 questions
        foreach ([1, 2, 3, 4] as $order) {
            Question::create(
                [
                    'interview_id' => $interview->id,
                    'question' => 'Q' . $order,
                    'order' => $order,
                    'time_limit' => 60,
                ]
            );
        }

        $submission = Submission::create(
            [
                'user_id' => $user->id,
                'interview_id' => $interview->id,
                'status' => 'in_progress',
            ]
        );

        // 2 completed answers out of 4 total questions -> 50%
        $q1Id = Question::where('order', 1)
            ->where('interview_id', $interview->id)
            ->value('id');
        $q2Id = Question::where('order', 2)
            ->where('interview_id', $interview->id)
            ->value('id');
        $q3Id = Question::where('order', 3)
            ->where('interview_id', $interview->id)
            ->value('id');

        SubmissionAnswer::create(
            [
                'submission_id' => $submission->id,
                'question_id' => $q1Id,
                'status' => 'completed',
            ]
        );
        SubmissionAnswer::create(
            [
                'submission_id' => $submission->id,
                'question_id' => $q2Id,
                'status' => 'completed',
            ]
        );
        SubmissionAnswer::create(
            [
                'submission_id' => $submission->id,
                'question_id' => $q3Id,
                'status' => 'recording',
            ]
        );
        // question 4 has no answer yet

        $this->assertSame(50, $submission->getProgressPercentage());
    }

    /**
     * It should return zero when there are no questions.
     *
     * @return void
     */
    public function testGetProgressPercentageZeroWhenNoQuestions(): void
    {
        $user = User::factory()->create();
        $interview = Interview::create(
            [
                'title' => 'Empty Interview',
                'description' => 'No questions',
                'is_active' => true,
                'time_limit' => null,
                'created_by' => $user->id,
            ]
        );

        $submission = Submission::create(
            [
                'user_id' => $user->id,
                'interview_id' => $interview->id,
                'status' => 'in_progress',
            ]
        );

        $this->assertSame(0, $submission->getProgressPercentage());
    }
}

