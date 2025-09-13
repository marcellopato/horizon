<?php

namespace Tests\Feature;

use App\Models\Interview;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Feature tests for role-based authorization on routes.
 *
 * @category Tests
 *
 * @author   Horizon Team <dev@horizon.local>
 * @license  https://opensource.org/licenses/MIT MIT License
 *
 * @link     https://github.com/marcellopato/horizon
 */
class AuthorizationTest extends TestCase
{
    use WithFaker;

    /**
     * Helper to create user with a specific role.
     *
     * @param  string  $role  Role name
     */
    protected function createUser(string $role): User
    {
        return User::factory()->create(
            [
                'role' => $role,
            ]
        );
    }

    /**
     * Guests should be redirected to login on protected pages.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/interviews');
        $response->assertRedirect('/login');
    }

    /**
     * Candidate must receive 403 on manage interview routes.
     */
    public function test_candidate_cannot_access_manage_routes(): void
    {
        $candidate = $this->createUser('candidate');
        $this->actingAs($candidate);

        // create
        $this->get('/interviews/create')->assertStatus(403);
        // store
        $this->post(
            '/interviews',
            [
                'title' => 'X',
                'description' => 'Y',
                'time_limit' => 10,
            ]
        )->assertStatus(403);
    }

    /**
     * Reviewer should access manage routes successfully.
     */
    public function test_reviewer_can_access_manage_routes(): void
    {
        $reviewer = $this->createUser('reviewer');
        $this->actingAs($reviewer);

        $this->get('/interviews/create')->assertOk();

        $resp = $this->post(
            '/interviews',
            [
                'title' => 'R Test',
                'description' => 'Reviewer can create',
                'time_limit' => 15,
                'questions' => [
                    ['question' => 'Why us?', 'time_limit' => 60],
                ],
            ]
        );
        $resp->assertRedirect();
    }

    /**
     * Admin should access manage routes successfully.
     */
    public function test_admin_can_access_manage_routes(): void
    {
        $admin = $this->createUser('admin');
        $this->actingAs($admin);

        $this->get('/interviews/create')->assertOk();
    }

    /**
     * Candidate can browse and start, but not access review area.
     */
    public function test_candidate_can_start_and_submit_but_not_review(): void
    {
        $reviewer = $this->createUser('reviewer');
        $this->actingAs($reviewer);

        // reviewer creates interview
        $this->post(
            '/interviews',
            [
                'title' => 'Interview',
                'description' => 'Desc',
                'time_limit' => 10,
                'questions' => [
                    ['question' => 'Q1', 'time_limit' => 60],
                ],
            ]
        )->assertRedirect();

        /**
         * Interview instance created by reviewer
         *
         * @var Interview $interview
         */
        $interview = Interview::latest('id')->first();

        // switch to candidate
        $candidate = $this->createUser('candidate');
        $this->actingAs($candidate);

        // candidate can view interviews index and show
        $this->get('/interviews')->assertOk();
        $this->get('/interviews/'.$interview->id)->assertOk();

        // candidate cannot review
        $this->get('/submissions')->assertStatus(403);
    }
}
