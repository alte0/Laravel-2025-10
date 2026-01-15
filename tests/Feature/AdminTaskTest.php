<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\{Task, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\{DataProvider, Group};
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

#[Group('AdminTaskTest')]
class AdminTaskTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use RefreshDatabase;

    const HTTP_OK = Response::HTTP_OK;
    const HTTP_FOUND = Response::HTTP_FOUND;
    const HTTP_FORBIDDEN = Response::HTTP_FORBIDDEN;
    private User $user;
    private User $userAdmin;
    private Task $task;
    private int $taskId;

    public static function accessDataRouteProvider(): array
    {
        // тип пользователя; http method; роут; нужен ли id задачи; статус ответа; нужен ли id задачи редиректа; роутер редиректа
        return [
            ['guest', 'get', 'adminTasks.tasks.index', false, self::HTTP_FOUND, false, 'login'],
            ['guest', 'get', 'adminTasks.tasks.create', false, self::HTTP_FOUND, false, 'login'],
            ['guest', 'post', 'adminTasks.tasks.store', false, self::HTTP_FOUND, false, 'login'],
            ['guest', 'get', 'adminTasks.tasks.show', true, self::HTTP_FOUND, false, 'login'],
            ['guest', 'get', 'adminTasks.tasks.edit', true, self::HTTP_FOUND, false, 'login'],
            ['guest', 'put', 'adminTasks.tasks.update', true, self::HTTP_FOUND, false, 'login'],
            ['guest', 'delete', 'adminTasks.tasks.destroy', true, self::HTTP_FOUND, false, 'login'],

            ['regular', 'get', 'adminTasks.tasks.index', false, self::HTTP_FORBIDDEN, false,],
            ['regular', 'get', 'adminTasks.tasks.create', false, self::HTTP_FORBIDDEN, false,],
            ['regular', 'post', 'adminTasks.tasks.store', false, self::HTTP_FORBIDDEN, false,],
            ['regular', 'get', 'adminTasks.tasks.show', true, self::HTTP_FORBIDDEN, false,],
            ['regular', 'get', 'adminTasks.tasks.edit', true, self::HTTP_FORBIDDEN, false,],
            ['regular', 'put', 'adminTasks.tasks.update', true, self::HTTP_FORBIDDEN, false,],
            ['regular', 'delete', 'adminTasks.tasks.destroy', true, self::HTTP_FORBIDDEN, false,],

            ['admin', 'get', 'adminTasks.tasks.index', false, self::HTTP_OK, false,],
            ['admin', 'get', 'adminTasks.tasks.create', false, self::HTTP_OK, false,],
            ['admin', 'post', 'adminTasks.tasks.store', true, self::HTTP_FOUND, true, 'adminTasks.tasks.show'],
            ['admin', 'get', 'adminTasks.tasks.show', true, self::HTTP_OK, false,],
            ['admin', 'get', 'adminTasks.tasks.edit', true, self::HTTP_OK, false,],
            ['admin', 'put', 'adminTasks.tasks.update', true, self::HTTP_FOUND, true, 'adminTasks.tasks.edit'],
            ['admin', 'delete', 'adminTasks.tasks.destroy', true, self::HTTP_FOUND, false, 'adminTasks.tasks.index'],
        ];
    }

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
    }
}
