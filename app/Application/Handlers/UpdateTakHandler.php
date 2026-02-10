<?php

namespace App\Application\Handlers;

use App\Application\DTO\Task\UpdateTaskRequestDTO;
use App\Domain\TaskSubDomain\Models\Task;
use App\Domain\TaskSubDomain\Repositories\TaskRepositoryInterface;
use App\Domain\TaskSubDomain\VO\AuthorId;
use App\Domain\TaskSubDomain\VO\Description;
use App\Domain\TaskSubDomain\VO\EndDate;
use App\Domain\TaskSubDomain\VO\ExecutorId;
use App\Domain\TaskSubDomain\VO\Id;
use App\Domain\TaskSubDomain\VO\StartDate;
use App\Domain\TaskSubDomain\VO\Title;

final readonly class UpdateTakHandler
{
    final public function __construct(
        private TaskRepositoryInterface $taskRepository)
    {
    }

    final public function handle(UpdateTaskRequestDTO $dto): void
    {
        $taskOld = $this->taskRepository->fetchAuthorIdAndExecutorId($dto->getId());

        $authorId = new AuthorId($taskOld->getAuthorId());
        $executorId = new ExecutorId($taskOld->getExecutorId());

        $id = new Id($dto->getId());
        $title = new Title($dto->getTitle());
        $description = new Description($dto->getDescription());
        $startDate = new StartDate($dto->getStart());
        $endDate = new EndDate($dto->getEnd());

        $taskUpdate = new Task(
            $id,
            $title,
            $description,
            $authorId,
            $executorId,
            $startDate,
            $endDate,
        );

        $this->taskRepository->updateModel($taskUpdate);
    }
}
