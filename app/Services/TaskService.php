<?php

namespace App\Services;

use App\DTO\CreateTaskRequestDTO;
use App\DTO\UpdateTaskRequestDTO;
use App\Models\Task;
use App\Repositories\TaskRepo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final readonly class TaskService
{
    public function __construct(
        private TaskRepo $taskRepo,
    )
    {
    }

    public function getItems(): Collection
    {
        return $this->taskRepo->getItems();
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
        return $this->taskRepo->create($createTaskRequestDTO);
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
