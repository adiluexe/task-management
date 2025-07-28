<?php

namespace Tests\Feature\Admin;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->user = User::factory()->create(['is_admin' => false]);
    }

    public function test_admin_can_access_dashboard_stats()
    {
        // Create some test data
        Task::factory()->count(5)->create(['user_id' => $this->user->id]);
        Task::factory()->count(3)->create(['user_id' => $this->user->id, 'status' => 'completed']);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/dashboard-stats');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'stats' => [
                    'totalUsers',
                    'totalTasks',
                    'completedTasks',
                    'pendingTasks',
                    'completionRate',
                ]
            ]);
    }

    public function test_regular_user_cannot_access_admin_dashboard()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/dashboard-stats');

        $response->assertStatus(403);
    }

    public function test_admin_can_view_all_users()
    {
        User::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'users' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'tasks_count',
                        'completed_tasks',
                        'pending_tasks',
                    ]
                ],
                'pagination'
            ]);
    }

    public function test_admin_can_toggle_user_admin_status()
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->patchJson("/api/admin/users/{$this->user->id}/toggle-admin");

        $response->assertStatus(200);

        $this->user->refresh();
        $this->assertTrue($this->user->is_admin);
    }

    public function test_admin_cannot_toggle_own_admin_status()
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->patchJson("/api/admin/users/{$this->admin->id}/toggle-admin");

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Cannot change your own admin status'
            ]);
    }

    public function test_admin_can_delete_user()
    {
        $userToDelete = User::factory()->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/admin/users/{$userToDelete->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', [
            'id' => $userToDelete->id,
        ]);
    }

    public function test_admin_cannot_delete_themselves()
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/admin/users/{$this->admin->id}");

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Cannot delete your own account'
            ]);
    }

    public function test_admin_can_view_recent_activity()
    {
        // Create some test data
        Task::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/recent-activity');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'activity' => [
                    '*' => [
                        'id',
                        'type',
                        'description',
                        'created_at',
                    ]
                ]
            ]);
    }

    public function test_regular_user_cannot_access_admin_endpoints()
    {
        $endpoints = [
            '/api/admin/users',
            '/api/admin/recent-activity',
        ];

        foreach ($endpoints as $endpoint) {
            $response = $this->actingAs($this->user, 'sanctum')
                ->getJson($endpoint);

            $response->assertStatus(403);
        }
    }
}
