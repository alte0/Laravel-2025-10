<?php

namespace App\Domain\TaskSubDomain\Repositories;

use App\Application\DTO\ResultId;
use App\Application\DTO\Task\GetAuthorAndExecutor;
use App\Domain\TaskSubDomain\Models\Task\Task as TaskDomainModel;

interface TaskRepositoryInterface
{
    public function fetchAuthorIdAndExecutorId(int $taskId): GetAuthorAndExecutor;

    public function save(TaskDomainModel $newTask): ResultId;

    public function getDateFormatEloquentModel(): string;

    public function update(TaskDomainModel $taskUpdate): bool;
}
