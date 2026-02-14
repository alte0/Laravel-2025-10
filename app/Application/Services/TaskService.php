<?php

namespace App\Application\Services;


use App\Application\DTO\Task\CreateTaskRequestDTO;
use App\Application\DTO\Task\UpdateTaskRequestDTO;
use App\Domain\TaskSubDomain\Models\Task\AuthorId;
use App\Domain\TaskSubDomain\Models\Task\Description;
use App\Domain\TaskSubDomain\Models\Task\ExecutorId;
use App\Domain\TaskSubDomain\Models\Task\Id;
use App\Domain\TaskSubDomain\Models\Task\Task;
use App\Domain\TaskSubDomain\Models\Task\Title;
use App\Domain\TaskSubDomain\Repositories\TaskRepositoryInterface;
use DateTimeImmutable;

final readonly class TaskService
{
    final public function __construct(
        private TaskRepositoryInterface $taskRepository)
    {
    }

    public function create(CreateTaskRequestDTO $dto): int
    {
        $id = new Id();
        $title = new Title($dto->getTitle());
        $description = new Description($dto->getDescription());
        $authorId = new AuthorId($dto->getAuthorId());
        $executorId = new ExecutorId($dto->getExecutorId());
        $startDate = new DateTimeImmutable($dto->getStart());
        $endDate = new DateTimeImmutable($dto->getEnd());

        $task = new Task(
            $id,
            $title,
            $description,
            $authorId,
            $executorId,
            $startDate,
            $endDate,
        );

        $resultId = $this->taskRepository->save($task);

        return $resultId->getId();
    }

    final public function update(UpdateTaskRequestDTO $dto): void
    {
        $taskOld = $this->taskRepository->fetchAuthorIdAndExecutorId($dto->getId());

        $authorId = new AuthorId($taskOld->getAuthorId());
        $executorId = new ExecutorId($taskOld->getExecutorId());

        $id = new Id($dto->getId());
        $title = new Title($dto->getTitle());
        $description = new Description($dto->getDescription());
        $startDate = new DateTimeImmutable($dto->getStart());
        $endDate = new DateTimeImmutable($dto->getEnd());

        $taskUpdate = new Task(
            $id,
            $title,
            $description,
            $authorId,
            $executorId,
            $startDate,
            $endDate,
        );

        $this->taskRepository->update($taskUpdate);
    }
}
