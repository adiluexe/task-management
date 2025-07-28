<?php

namespace Tests\Feature\Tasks;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_create_task()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/tasks', [
                'title' => 'Test Task',
                'description' => 'Test Description',
                'priority' => 'high',
                'status' => 'pending',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'title',
                    'description',
                    'priority',
                    'status',
                ],
                'message'
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_can_view_their_tasks()
    {
        Task::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'priority',
                        'status',
                    ]
                ],
                'pagination'
            ]);
    }

    public function test_user_can_update_their_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/tasks/{$task->id}", [
                'title' => 'Updated Task Title',
                'status' => 'completed',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task Title',
            'status' => 'completed',
        ]);
    }

    public function test_user_can_delete_their_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_user_cannot_access_other_users_tasks()
    {
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(403);
    }

    public function test_user_can_toggle_task_status()
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/tasks/{$task->id}/toggle-status");

        $response->assertStatus(200);

        $task->refresh();
        $this->assertEquals('completed', $task->status);
    }

    public function test_task_validation_rules()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/tasks', [
                'title' => '', // Required field
                'priority' => 'invalid', // Invalid value
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'priority']);
    }

    public function test_task_filtering_by_status()
    {
        Task::factory()->create(['user_id' => $this->user->id, 'status' => 'pending']);
        Task::factory()->create(['user_id' => $this->user->id, 'status' => 'completed']);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/tasks?status=pending');

        $response->assertStatus(200);

        $tasks = $response->json('data');
        $this->assertCount(1, $tasks);
        $this->assertEquals('pending', $tasks[0]['status']);
    }

    public function test_task_search_functionality()
    {
        Task::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Important Meeting'
        ]);
        Task::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Buy Groceries'
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/tasks?search=Meeting');

        $response->assertStatus(200);

        $tasks = $response->json('data');
        $this->assertCount(1, $tasks);
        $this->assertStringContainsString('Meeting', $tasks[0]['title']);
    }
}
