<?php

namespace App\Domain\TaskSubDomain\Repositories;

use App\Application\DTO\ResultId;
use App\Application\DTO\Task\GetAuthorAndExecutor;
use App\Domain\TaskSubDomain\Models\Task as TaskDomainModel;
use Illuminate\Database\Eloquent\Model;

interface TaskRepositoryInterface
{
    public function fetchAuthorIdAndExecutorId(int $taskId): GetAuthorAndExecutor;

    public function saveModel(TaskDomainModel $newTask): ResultId;

    public function updateModel(TaskDomainModel $taskUpdate): bool;
}
