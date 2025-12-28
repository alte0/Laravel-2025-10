<?php

namespace Tests\Feature;

//use App\DTO\CreateTaskRequestDTO;
//use App\DTO\UpdateTaskRequestDTO;
use App\Models\Task;
use App\Models\User;
//use App\Repositories\TaskRepo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
//use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('AdminTaskTest')]
class AdminTaskTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use RefreshDatabase;

    #[DataProvider('accessDataRouteProvider')]
    public function test_user_access_to_routes(
        string $role, string $httpMethod, string $routeName, bool $isNeedTaskIdRoute,
        int    $expectedStatus, bool $isNeedTaskIdRouteRedirect, ?string $redirectNameTo = null
    ): void
    {
        $user = match ($role) {
            'guest' => null,
            'regular' => $this->user,
            'admin' => $this->userAdmin,
        };

        if ($user instanceof User) {
            $this->actingAs($user);
        }

        $routeParams = $routeParamsRedirect = [];

        if ($isNeedTaskIdRoute) {
            $routeParams['task'] = $this->taskId;
        }

        if (in_array($httpMethod, ['post', 'put',])) {
            $data = [
                'title' => $this->task->title,
                'description' => $this->task->description,
                'start' => $this->task->start_date,
                'end' => $this->task->end_date,
            ];

            if ($httpMethod === 'put') {
                $data['id'] = $this->task->id;
            }
        }

        $url = route($routeName, $routeParams);

        $response = $this->{$httpMethod}($url, $data ?? []);

        if ($isNeedTaskIdRouteRedirect) {
            $routeParamsRedirect['task'] = Task::query()->orderByDesc('id')->first()->id;
        }

        $response->assertStatus($expectedStatus);

        if (!empty($redirectNameTo)) {
            $response->assertRedirect(route($redirectNameTo, $routeParamsRedirect));
        }
    }

    public static function accessDataRouteProvider(): array
    {
        // тип пользователя; http method; роут; нужен ли id задачи; статус ответа; нужен ли id задачи редиректа; роутер редиректа
        return [
            ['guest', 'get', 'adminTasks.tasks.index', false, Response::HTTP_FOUND, false, 'login'],
            ['guest', 'get', 'adminTasks.tasks.create', false, Response::HTTP_FOUND, false, 'login'],
            ['guest', 'post', 'adminTasks.tasks.store', false, Response::HTTP_FOUND, false, 'login'],
            ['guest', 'get', 'adminTasks.tasks.show', true, Response::HTTP_FOUND, false, 'login'],
            ['guest', 'get', 'adminTasks.tasks.edit', true, Response::HTTP_FOUND, false, 'login'],
            ['guest', 'put', 'adminTasks.tasks.update', true, Response::HTTP_FOUND, false, 'login'],
            ['guest', 'delete', 'adminTasks.tasks.destroy', true, Response::HTTP_FOUND, false, 'login'],

            ['regular', 'get', 'adminTasks.tasks.index', false, Response::HTTP_FORBIDDEN, false,],
            ['regular', 'get', 'adminTasks.tasks.create', false, Response::HTTP_FORBIDDEN, false,],
            ['regular', 'post', 'adminTasks.tasks.store', false, Response::HTTP_FORBIDDEN, false,],
            ['regular', 'get', 'adminTasks.tasks.show', true, Response::HTTP_FORBIDDEN, false,],
            ['regular', 'get', 'adminTasks.tasks.edit', true, Response::HTTP_FORBIDDEN, false,],
            ['regular', 'put', 'adminTasks.tasks.update', true, Response::HTTP_FORBIDDEN, false,],
            ['regular', 'delete', 'adminTasks.tasks.destroy', true, Response::HTTP_FORBIDDEN, false,],

            ['admin', 'get', 'adminTasks.tasks.index', false, Response::HTTP_OK, false,],
            ['admin', 'get', 'adminTasks.tasks.create', false, Response::HTTP_OK, false,],
            ['admin', 'post', 'adminTasks.tasks.store', true, Response::HTTP_FOUND, true, 'adminTasks.tasks.show'],
            ['admin', 'get', 'adminTasks.tasks.show', true, Response::HTTP_OK, false,],
            ['admin', 'get', 'adminTasks.tasks.edit', true, Response::HTTP_OK, false,],
            ['admin', 'put', 'adminTasks.tasks.update', true, Response::HTTP_FOUND, true, 'adminTasks.tasks.edit'],
            ['admin', 'delete', 'adminTasks.tasks.destroy', true, Response::HTTP_FOUND, false, 'adminTasks.tasks.index'],
        ];
    }

    private User $user;
    private User $userAdmin;
    private Task $task;
    private int $taskId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'User',
            'is_admin' => false,
        ]);

        $this->userAdmin = User::factory()->create([
            'name' => 'UserAdmin',
            'is_admin' => true,
        ]);

        $this->task = Task::factory()->count(1)->create()->first();
        $this->taskId = $this->task->getKey();

        /*$mock = Mockery::mock(TaskRepo::class);

        $mock
            ->makePartial()
            ->shouldReceive('create')
            ->with(Mockery::type(CreateTaskRequestDTO::class))
            ->andReturnUsing(function (CreateTaskRequestDTO $dto) use ($selfTask) {
                $newTask = Task::factory()->make([
                    'id' => $selfTask->id,
                    'title' => $dto->getTitle(),
                    'author_id' => $dto->getAuthorId(),
                    'description' => $dto->getDescription(),
                    'start_date' => $dto->getStart(),
                    'end_date' => $dto->getEnd(),
                ]);

                return $newTask->getKey();
            });

        $mock
            ->makePartial()
            ->shouldReceive('find')
            ->with($taskId)
            ->andReturnUsing(function ($id) use ($selfTask) {
                return $selfTask;
            });

        $mock
            ->shouldReceive('update')
            ->with(Mockery::type(UpdateTaskRequestDTO::class))
            ->andReturnUsing(function (UpdateTaskRequestDTO $dto) use ($taskId) {
                return $taskId;
            });

        $this->app->instance(TaskRepo::class, $mock);*/
    }
}
