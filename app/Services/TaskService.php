<?php

namespace App\Services;

use App\DTO\CreateTaskRequestDTO;
use App\DTO\UpdateTaskRequestDTO;
use App\Jobs\NotifyTelegramJob;
use App\Models\Task;
use App\Repositories\TaskRepo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;

class TaskService
{
    const CACHE_KEY_LAST_TASKS = 'lastTasks';
    const CACHE_TTL_LAST_TASKS = 60;
    const COUNT_LAST_TASKS = 10;

    public function __construct(
        private TaskRepo $taskRepo
    )
    {
    }

    public function getItems(): Collection
    {
        return $this->taskRepo->getItems();
    }

    public function getLastItems(bool $withCache = true): Collection
    {
        if ($withCache) {
            return Cache::remember(
                self::CACHE_KEY_LAST_TASKS,
                self::CACHE_TTL_LAST_TASKS,
                fn() => $this->taskRepo->getLastItems(self::COUNT_LAST_TASKS)
            );
        } else {
            return $this->taskRepo->getLastItems(self::COUNT_LAST_TASKS);
        }
    }

    public function warmingUpCacheLastTasks(): void
    {
        Cache::forget(self::CACHE_KEY_LAST_TASKS);

        $this->getLastItems();
    }

    public function find(string $id): Task
    {
        $task = $this->taskRepo->find($id);

        if ($task === null) {
            throw new ModelNotFoundException();
        }

        return $task;
    }

    public function create(CreateTaskRequestDTO $createTaskRequestDTO): int
    {
        $id = $this->taskRepo->create($createTaskRequestDTO);

        Cache::forget(self::CACHE_KEY_LAST_TASKS);

        NotifyTelegramJob::dispatch('Create new task - ' . route('adminTasks.tasks.show', ['task' => $id]));

        return $id;
    }

    public function update(UpdateTaskRequestDTO $updateTaskRequestDTO): bool
    {
        return $this->taskRepo->update($updateTaskRequestDTO);
    }

    public function delete($id): bool
    {
        return $this->taskRepo->delete($id);
    }
}
