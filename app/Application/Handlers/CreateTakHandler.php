<?php

namespace App\Application\Handlers;

use App\Application\DTO\Task\CreateTaskRequestDTO;
use App\Domain\TaskSubDomain\Models\Task;
use App\Domain\TaskSubDomain\Repositories\TaskRepositoryInterface;
use App\Domain\TaskSubDomain\VO\AuthorId;
use App\Domain\TaskSubDomain\VO\Description;
use App\Domain\TaskSubDomain\VO\EndDate;
use App\Domain\TaskSubDomain\VO\ExecutorId;
use App\Domain\TaskSubDomain\VO\Id;
use App\Domain\TaskSubDomain\VO\StartDate;
use App\Domain\TaskSubDomain\VO\Title;

final readonly class CreateTakHandler
{
    final public function __construct(
        private TaskRepositoryInterface $taskRepository)
    {
    }

    final public function handle(CreateTaskRequestDTO $dto): int
    {
        $id = new Id();
        $title = new Title($dto->getTitle());
        $description = new Description($dto->getDescription());
        $authorId = new AuthorId($dto->getAuthorId());
        $executorId = new ExecutorId($dto->getExecutorId());
        $startDate = new StartDate($dto->getStart());
        $endDate = new EndDate($dto->getEnd());

        $task = new Task(
            $id,
            $title,
            $description,
            $authorId,
            $executorId,
            $startDate,
            $endDate,
        );

        $resultId = $this->taskRepository->saveModel($task);

        return $resultId->getId();
    }
}
