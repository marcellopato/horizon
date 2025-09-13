<?php

namespace Tests\Feature;

use App\Models\Interview;
use App\Models\Question;
use App\Models\Submission;
use App\Models\SubmissionAnswer;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DownloadEndpointsTest extends TestCase
{
    private function seedSubmissionWithVideo(): array
    {
        $reviewer = User::factory()->create(['role' => 'reviewer']);
        $candidate = User::factory()->create(['role' => 'candidate']);

        $interview = Interview::create([
            'title' => 'Dev',
            'description' => 'desc',
            'time_limit' => 30,
            'created_by' => $reviewer->id,
            'is_active' => true,
        ]);

        $question = Question::create([
            'interview_id' => $interview->id,
            'question' => 'Q1',
            'order' => 1,
            'time_limit' => 1,
        ]);

        $submission = Submission::create([
            'user_id' => $candidate->id,
            'interview_id' => $interview->id,
            'status' => 'completed',
        ]);

        Storage::fake('public');
        $path = "interviews/{$interview->id}/submissions/{$submission->id}/answer.webm";
        Storage::disk('public')->put($path, 'dummy');

        $answer = SubmissionAnswer::create([
            'submission_id' => $submission->id,
            'question_id' => $question->id,
            'video_path' => $path,
            'recording_duration' => 5,
            'status' => 'completed',
        ]);

        return compact('reviewer', 'candidate', 'submission', 'answer');
    }

    public function testReviewerCanDownloadSubmissionZip(): void
    {
        $data = $this->seedSubmissionWithVideo();
        $reviewer = $data['reviewer'];
        $submission = $data['submission'];

        $this->actingAs($reviewer)
            ->get(route('submissions.download', $submission))
            ->assertOk()
            ->assertHeader('content-type', 'application/zip');
    }

    public function testReviewerCanDownloadSingleAnswer(): void
    {
        $data = $this->seedSubmissionWithVideo();
        $reviewer = $data['reviewer'];
        $answer = $data['answer'];

        $this->actingAs($reviewer)
            ->get(route('submission-answers.download', $answer))
            ->assertOk();
    }

    public function testCandidateCannotDownload(): void
    {
        $data = $this->seedSubmissionWithVideo();
        $candidate = $data['candidate'];
        $submission = $data['submission'];
        $answer = $data['answer'];

        $this->actingAs($candidate)
            ->get(route('submissions.download', $submission))
            ->assertForbidden();

        $this->actingAs($candidate)
            ->get(route('submission-answers.download', $answer))
            ->assertForbidden();
    }

    public function testGuestIsRedirectedToLogin(): void
    {
        $data = $this->seedSubmissionWithVideo();
        $submission = $data['submission'];
        $answer = $data['answer'];

        $this->get(route('submissions.download', $submission))
            ->assertRedirect();

        $this->get(route('submission-answers.download', $answer))
            ->assertRedirect();
    }
}
