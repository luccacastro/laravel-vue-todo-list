<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Exceptions;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $task;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->task = Task::create([
            'title' => 'Setup Task',
            'description' => 'This task is set up before each test',
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_can_list_tasks_for_authenticated_user()
    {
        $response = $this->actingAs($this->user)->getJson('/tasks');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_can_create_a_task()
    {
        $taskData = [
            'title' => 'New Task',
            'description' => 'Description for new task',
        ];

        $response = $this->actingAs($this->user)->postJson('/tasks', $taskData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', [
            'title' => $taskData['title'],
            'description' => $taskData['description'],
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_can_mark_a_task_as_complete()
    {
        $response = $this->actingAs($this->user)->patchJson("/tasks/{$this->task->id}/complete");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Task marked as complete successfully']);
        $this->assertDatabaseHas('tasks', [
            'id' => $this->task->id,
            'is_completed' => true,
        ]);
    }

    /** @test */
    public function it_can_delete_a_task()
    {
        $response = $this->actingAs($this->user)->deleteJson("/tasks/{$this->task->id}");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Task deleted successfully']);
        $this->assertDatabaseMissing('tasks', ['id' => $this->task->id]);
    }

    /** @test */
    public function it_fails_to_delete_a_task_of_another_user()
    {
        $otherUser = User::create([
            'name' => 'Other User',
            'email' => 'otheruser@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($otherUser)->deleteJson("/tasks/{$this->task->id}");

        $response->assertStatus(403);
        $response->assertJson(['error' => 'Unauthorized to delete this task']);
    }

    /** @test */
    public function it_returns_404_for_non_existent_task_on_mark_complete()
    {
        $response = $this->actingAs($this->user)->patchJson('/tasks/9999/complete');

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Record Not Found']);
    }

    /** @test */
    public function it_returns_404_for_non_existent_task_on_delete()
    {
        $response = $this->actingAs($this->user)->deleteJson('/tasks/9999');

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Record Not Found']);
    }

    /** @test */
    public function it_fails_to_create_a_task_without_title()
    {
        $taskData = [
            'title' => '',
            'description' => 'Description without title',
        ];

        $response = $this->actingAs($this->user)->postJson('/tasks', $taskData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

    /** @test */
    public function it_fails_to_create_a_task_with_long_title()
    {
        $taskData = [
            'title' => str_repeat('a', 256),
            'description' => 'Description with too long title',
        ];

        $response = $this->actingAs($this->user)->postJson('/tasks', $taskData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

    /** @test */
    public function it_fails_to_mark_a_task_as_complete_if_not_owner()
    {
        $otherUser = User::create([
            'name' => 'Other User',
            'email' => 'otheruser@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($otherUser)->patchJson("/tasks/{$this->task->id}/complete");
        $response->assertStatus(403);
        $response->assertJson(['error' =>'Unauthorized to mark this task as complete']);
    }


    /** @test */
    public function it_fails_to_delete_a_task_if_not_owner()
    {
        $otherUser = User::create([
            'name' => 'Other User',
            'email' => 'otheruser@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($otherUser)->deleteJson("/tasks/{$this->task->id}");

        $response->assertStatus(403);
        $response->assertJson(['error' => 'Unauthorized to delete this task']);
    }
}
