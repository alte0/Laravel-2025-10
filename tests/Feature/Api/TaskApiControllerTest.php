<?php

namespace Tests\Feature\API;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TaskApiControllerTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;
    private int $countTasks = 5;
    private array $structureItem = [
        'id',
        'title',
        'description',
        'authorId',
        'executorId',
        'createdAt',
        'updatedAt',
        'startDate',
        'endDate',
    ];

    private string $apiPath = '/api/v1';

    public function test_list(): void
    {
        Passport::actingAs($this->user);

        $response = $this->getJson("{$this->apiPath}/tasks");

        $response
            ->assertOk()
            ->assertJsonCount($this->countTasks, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->structureItem,
                ]
            ]);
    }

    public function test_list_guest(): void
    {
        $this->actingAsGuest();

        $response = $this->getJson("{$this->apiPath}/tasks");

        $response->assertUnauthorized();
    }

    public function test_show(): void
    {
        Passport::actingAs($this->user);

        $taskId = fake()->numberBetween(1, $this->countTasks);
        $response = $this->getJson("{$this->apiPath}/tasks/" . $taskId);

        $response
            ->assertOk()
            ->assertJsonStructure($this->structureItem);
    }

    public function test_store(): void
    {
        Passport::actingAs($this->user);

        $data = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'start' => '2026-02-15 09:00:00',
            'end' => '2026-02-15 17:00:00',
            'executor_id' => $this->user->id,
        ];

        $response = $this->postJson("{$this->apiPath}/tasks", $data);

        $response
            ->assertCreated()
            ->assertJson([
                'message' => 'Task created',
            ])
            ->assertJsonStructure([
                'message',
                'id',
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'author_id' => $this->user->id,
            'executor_id' => $this->user->id,
        ]);
    }

    public function test_update(): void
    {
        Passport::actingAs($this->user);

        $task = Task::factory()->create([
            'title' => 'Original Title',
            'description' => 'Original Description',
        ]);

        $data = [
            'title' => 'Updated Task',
            'description' => 'Updated Description',
            'start' => '2026-02-15 10:00:00',
            'end' => '2026-02-15 18:00:00',
        ];

        $response = $this->putJson("{$this->apiPath}/tasks/{$task->id}", $data);

        $response
            ->assertOk()
            ->assertJson([
                'message' => 'Task updated',
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task',
            'description' => 'Updated Description',
        ]);
    }

    public function test_destroy(): void
    {
        Passport::actingAs($this->user);

        $task = Task::factory()->create([
            'title' => 'Task to Delete',
            'description' => 'This task will be deleted',
        ]);

        $response = $this->deleteJson("{$this->apiPath}/tasks/{$task->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        Task::factory()->count($this->countTasks)->create();
    }
}
