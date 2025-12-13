<?php

namespace Tests\Feature;

use App\DTO\CreateTaskRequestDTO;
use App\DTO\UpdateTaskRequestDTO;
use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepo;
use Illuminate\Http\Response;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('AdminTaskControllerTest')]
class AdminTaskTest extends TestCase
{
    private User $user;
    private User $userAdmin;

    /**
     * @var array{int, User}
     */
    private array $users;

    private Task $task;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->make(['id' => 1]);
        $this->user->is_admin = false;

        $this->userAdmin = User::factory()->make(['id' => 2]);
        $this->userAdmin->is_admin = true;

        $this->users = [
            $this->user,
            $this->userAdmin,
        ];

        $this->task = Task::factory()->count(1)->make(['id' => 1])->first();

        $this->urlIndex = route('adminTasks.tasks.index');
        $this->urlStore = route('adminTasks.tasks.store');
        $this->urlCreate = route('adminTasks.tasks.create');
        $this->urlShow = route('adminTasks.tasks.show', ['task' => $this->task->id]);

        $this->urlUpdate = route('adminTasks.tasks.update', ['task' => $this->task->id]);
        $this->urlDestroy = route('adminTasks.tasks.destroy', ['task' => $this->task->id]);

        $this->urlEdit = route('adminTasks.tasks.edit', ['task' => $this->task->id]);

        $this->urlWithoutParams = [$this->urlIndex, $this->urlCreate];
        $this->urlWithParams = [$this->urlShow, $this->urlEdit];

        $this->urls = [
            $this->urlIndex,
            $this->urlStore,
            $this->urlShow,
            $this->urlCreate,
            $this->urlUpdate,
            $this->urlDestroy,
            $this->urlEdit
        ];

    }
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_create_task_request_dto()
    {
        $title = 'title';
        $description = 'description';
        $start = '12-09-2025';
        $end = '13-09-2025';
        $authorId = 1;

        $createTaskRequestDTO = new CreateTaskRequestDTO(
            $title,
            $description,
            $start,
            $end,
            $authorId
        );

        $this->assertEquals($title, $createTaskRequestDTO->getTitle());
        $this->assertEquals($description, $createTaskRequestDTO->getDescription());
        $this->assertEquals($start, $createTaskRequestDTO->getStart());
        $this->assertEquals($end, $createTaskRequestDTO->getEnd());
        $this->assertEquals($authorId, $createTaskRequestDTO->getAuthorId());
    }

    public function test_update_task_request_dto()
    {
        $id = $this->task->id;
        $title = 'title 2';
        $description = 'description 2';
        $start = '10-09-2025';
        $end = '12-09-2025';

        $updateTaskRequestDTO = new UpdateTaskRequestDTO(
            $id,
            $title,
            $description,
            $start,
            $end
        );

        $this->assertEquals($title, $updateTaskRequestDTO->getTitle());
        $this->assertEquals($description, $updateTaskRequestDTO->getDescription());
        $this->assertEquals($start, $updateTaskRequestDTO->getStart());
        $this->assertEquals($end, $updateTaskRequestDTO->getEnd());
        $this->assertEquals($id, $updateTaskRequestDTO->getId());
    }

    public function test_pages_for_guest(): void
    {
        foreach ($this->urls as $url) {
            $response = $this
                ->actingAsGuest()
                ->get($url);

            $response->assertStatus(Response::HTTP_FOUND);
        }
    }

    public function test_pages_for_user_not_admin(): void
    {
        foreach ($this->urls as $url) {
            $response = $this
                ->actingAs($this->user)
                ->get($url);

            $response
                ->assertStatus(Response::HTTP_FORBIDDEN);
        }
    }

    public function test_pages_without_params(): void
    {
        foreach ($this->urlWithoutParams as $url) {
            $response = $this
                ->actingAsGuest()
                ->get($url);

            $response->assertStatus(Response::HTTP_FOUND);
        }

        foreach ($this->urlWithoutParams as $url) {
            foreach ($this->users as $user) {
                $response = $this
                    ->actingAs($user)
                    ->get($url);

                $response->assertStatus($user->isAdmin() ? Response::HTTP_OK : Response::HTTP_FORBIDDEN);
            }
        }
    }

    public function test_pages_with_params(): void
    {
        $selfTask = $this->task;

        $this->partialMock(
            TaskRepo::class,
            function (MockInterface $mock) use ($selfTask) {
                $mock
                    ->shouldReceive('find')
                    ->andReturn($selfTask);
            }
        );

        foreach ($this->urlWithParams as $url) {
            foreach ($this->users as $user) {
                $response = $this
                    ->actingAs($user)
                    ->get($url);

                $response->assertStatus($user->isAdmin() ? Response::HTTP_OK : Response::HTTP_FORBIDDEN);
            }
        }
    }

    public function test_create_task(): void
    {
        $selfTask = $this->task;
        $storedTasks = collect();

        $mock = Mockery::mock(TaskRepo::class);

        $mock
            ->makePartial()
            ->shouldReceive('create')
            ->with(Mockery::type(CreateTaskRequestDTO::class))
            ->andReturnUsing(function (CreateTaskRequestDTO $dto) use ($storedTasks, $selfTask) {
                $newTask = Task::factory()->make([
                    'id' => $selfTask->id,
                    'title' => $dto->getTitle(),
                    'author_id' => $dto->getAuthorId(),
                    'description' => $dto->getDescription(),
                    'start_date' => $dto->getStart(),
                    'end_date' => $dto->getEnd(),
                ]);

                $storedTasks->push($newTask);

                return $newTask->getKey();
            });

        $mock
            ->makePartial()
            ->shouldReceive('find')
            ->with($selfTask->id)
            ->andReturnUsing(function ($id) use ($storedTasks) {
                return $storedTasks->firstWhere('id', $id);
            });

        $this->app->instance(TaskRepo::class, $mock);

        $response = $this
            ->actingAs($this->userAdmin)
            ->post(
                $this->urlStore,
                [
                    'title' => $this->task->title,
                    'description' => $this->task->description,
                    'start' => $this->task->start_date,
                    'end' => $this->task->end_date,
                ]
            );

        $response->assertRedirect();
    }

    public function test_update_task()
    {
        $selfTask = $this->task;

        $mock = Mockery::mock(TaskRepo::class)
            ->makePartial();

        $mock
            ->shouldReceive('update')
            ->with(Mockery::type(UpdateTaskRequestDTO::class))
            ->andReturnUsing(function (UpdateTaskRequestDTO $dto) {
                $updatedTask = Task::factory()->make([
                    'id' => $dto->getId(),
                    'title' => $dto->getTitle(),
                    'description' => $dto->getDescription(),
                    'start_date' => $dto->getStart(),
                    'end_date' => $dto->getEnd(),
                ]);

                return $updatedTask->getKey();
            });

//        $this->app->instance(TaskRepo::class, $mock);

        $response = $this
            ->actingAs($this->userAdmin)
            ->put(
                $this->urlUpdate,
                [
                    'id' => $selfTask->id,
                    'title' => $selfTask->title,
                    'description' => $selfTask->description,
                    'start' => $selfTask->start_date,
                    'end' => $selfTask->end_date,
                ]
            );

        $response->assertRedirect();
    }

    public function test_delete_task()
    {
        $selfTask = $this->task;
        $storedTasks = collect();
        $storedTasks->push($selfTask);

        $mock = Mockery::mock(TaskRepo::class)
            ->makePartial();

        $mock
            ->shouldReceive('find')
            ->with($selfTask->id)
            ->andReturnUsing(function ($id) use ($storedTasks) {
                return $storedTasks->firstWhere('id', $id);
            });

        $mock
            ->shouldReceive('delete')
            ->andReturn(true)
        ;

        $this->app->instance(TaskRepo::class, $mock);

        $response = $this
            ->actingAs($this->userAdmin)
            ->delete($this->urlDestroy);

        $response->assertRedirect();
    }
}
