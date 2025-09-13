<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Feature tests for Interview CRUD operations.
 *
 * @category Tests
 *
 * @author   Horizon
 * @license  MIT License
 *
 * @link     https://github.com/marcellopato/horizon
 */
class InterviewCrudTest extends TestCase
{
    use WithFaker;

    /**
     * Reviewer should be able to create an interview with questions.
     */
    public function test_reviewer_can_create_interview_with_questions(): void
    {
        $user = User::factory()->create(['role' => 'reviewer']);

        $payload = [
            'title' => 'Backend Engineer',
            'description' => 'Simple interview',
            'time_limit' => 30,
            'questions' => [
                ['question' => 'Tell us about yourself', 'time_limit' => 2],
                ['question' => 'Laravel strengths?', 'time_limit' => 3],
            ],
        ];

        $this->actingAs($user)
            ->post(route('interviews.store'), $payload)
            ->assertRedirect(route('interviews.index'));

        $this->assertDatabaseHas(
            'interviews',
            [
                'title' => 'Backend Engineer',
                'description' => 'Simple interview',
            ]
        );

        $this->assertDatabaseCount('questions', 2);
    }
}
