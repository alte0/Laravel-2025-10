<?php

namespace App\Repositories;

use App\DTO\CreateTaskRequestDTO;
use App\DTO\UpdateTaskRequestDTO;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

final class TaskRepo
{
    public function getItems(): Collection
    {
        return Task::all();
    }

    public function find(string $id): Task|null
    {
        return Task::query()->find($id);
    }

    public function create(CreateTaskRequestDTO $createTaskRequestDTO): int
    {
        $task = Task::query()->create([
            'title' => $createTaskRequestDTO->getTitle(),
            'description' => $createTaskRequestDTO->getDescription(),
            'start_date' => $createTaskRequestDTO->getStart(),
            'end_date' => $createTaskRequestDTO->getEnd(),
            'author_id' => $createTaskRequestDTO->getAuthorId(),
        ]);

        return $task->getKey();
    }

    public function update(UpdateTaskRequestDTO $updateTaskRequestDTO): bool
    {
        $result = Task::query()
            ->where('id', $updateTaskRequestDTO->getId())
            ->update([
                'title' => $updateTaskRequestDTO->getTitle(),
                'description' => $updateTaskRequestDTO->getDescription(),
                'start_date' => $updateTaskRequestDTO->getStart(),
                'end_date' => $updateTaskRequestDTO->getEnd(),
            ]);

        return (bool)$result;
    }
    public function delete(int $id): bool
    {
        return (bool)Task::query()->find($id)->delete();
    }
}
