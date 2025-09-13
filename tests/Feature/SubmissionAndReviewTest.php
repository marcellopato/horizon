<?php

namespace Tests\Feature;

use App\Models\Interview;
use App\Models\Question;
use App\Models\Submission;
use App\Models\SubmissionAnswer;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Feature tests for submission upload and review flows.
 *
 * @category Tests
 * @package  Feature
 * @author   Horizon
 * @license  MIT License
 * @link     https://github.com/marcellopato/horizon
 */
class SubmissionAndReviewTest extends TestCase
{
    /** @return array<string,mixed> */
    private function seedInterview(): array
    {
        $reviewer = User::factory()->create(['role' => 'reviewer']);
        $candidate = User::factory()->create(['role' => 'candidate']);

        $interview = Interview::create([
            'title' => 'Fullstack',
            'description' => 'desc',
            'time_limit' => 30,
            'created_by' => $reviewer->id,
            'is_active' => true,
        ]);

        $q1 = Question::create([
            'interview_id' => $interview->id,
            'question' => 'Q1',
            'order' => 1,
            'time_limit' => 2,
        ]);

        return compact('reviewer', 'candidate', 'interview', 'q1');
    }

    public function testCandidateCanUploadVideoAnswerAndSubmit(): void
    {
        Storage::fake('public');
        $data = $this->seedInterview();
        /** @var \App\Models\User $candidate */
        $candidate = $data['candidate'];
        /** @var \App\Models\Interview $interview */
        $interview = $data['interview'];
        /** @var \App\Models\Question $q1 */
        $q1 = $data['q1'];

        // Start submission
        $this->actingAs($candidate)
            ->post(route('submissions.start', $interview))
            ->assertRedirect();

        $submission = Submission::where('user_id', $candidate->id)
            ->where('interview_id', $interview->id)
            ->firstOrFail();

        // Upload video
        $file = UploadedFile::fake()->create('answer.webm', 1024, 'video/webm');
        $res = $this->actingAs($candidate)
            ->postJson(route('submissions.upload-video'), [
                'submission_id' => $submission->id,
                'question_id' => $q1->id,
                'video' => $file,
                'duration' => 10,
            ]);

        $res->assertOk()->assertJsonPath('success', true);

        $answer = SubmissionAnswer::where('submission_id', $submission->id)
            ->where('question_id', $q1->id)
            ->first();
        $this->assertNotNull($answer);
        $this->assertEquals('completed', $answer->status);
        Storage::disk('public')->assertExists($answer->video_path);

        // Submit submission
        $this->actingAs($candidate)
            ->post(route('submissions.submit', $submission))
            ->assertRedirect(route('interviews.index'));

        $submission->refresh();
        $this->assertTrue(in_array($submission->status, ['completed', 'reviewed', 'submitted'], true));
    }

    public function testReviewerCanSaveAnswerReviewAndOverallReview(): void
    {
        $data = $this->seedInterview();
        /** @var \App\Models\User $reviewer */
        $reviewer = $data['reviewer'];
        /** @var \App\Models\User $candidate */
        $candidate = $data['candidate'];
        /** @var \App\Models\Interview $interview */
        $interview = $data['interview'];
        /** @var \App\Models\Question $q1 */
        $q1 = $data['q1'];

        // Seed a completed submission/answer
        $submission = Submission::create([
            'user_id' => $candidate->id,
            'interview_id' => $interview->id,
            'status' => 'completed',
        ]);

        $answer = SubmissionAnswer::create([
            'submission_id' => $submission->id,
            'question_id' => $q1->id,
            'video_path' => 'interviews/'.$interview->id.'/submissions/'.$submission->id.'/fake.webm',
            'recording_duration' => 12,
            'status' => 'completed',
        ]);

        // Save per-answer review via JSON
        $this->actingAs($reviewer)
            ->postJson(route('submissions.save-review'), [
                'answer_id' => $answer->id,
                'score' => 8,
                'rating' => 'good',
                'comments' => 'Solid',
            ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $answer->refresh();
        $this->assertEquals(8, $answer->score);
        $this->assertEquals('good', $answer->rating);

        // Save overall review via JSON
        $this->actingAs($reviewer)
            ->postJson(route('submissions.save-overall-review'), [
                'submission_id' => $submission->id,
                'overall_score' => 90,
                'recommendation' => 'recommend',
                'overall_comments' => 'Hire',
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('submission.status', 'reviewed');

        $submission->refresh();
        $this->assertEquals('reviewed', $submission->status);
        $this->assertEquals(90, $submission->overall_score);
    }
}
