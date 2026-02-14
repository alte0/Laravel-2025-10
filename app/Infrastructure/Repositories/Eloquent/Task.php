<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Application\DTO\ResultId;
use App\Application\DTO\Task\GetAuthorAndExecutor;
use App\Domain\TaskSubDomain\Models\Task\Task as TaskDomainModel;
use App\Domain\TaskSubDomain\Repositories\TaskRepositoryInterface;
use App\Models\Task as TaskEloquent;

class Task implements TaskRepositoryInterface
{
    public function fetchAuthorIdAndExecutorId(int $taskId): GetAuthorAndExecutor
    {
        $taskData = TaskEloquent::query()->find($taskId, ['author_id', 'executor_id'])->toArray();
        $authorAndExecutor = new GetAuthorAndExecutor(intval($taskData['author_id']), intval($taskData['executor_id']));

        return $authorAndExecutor;
    }

    public function save(TaskDomainModel $newTask): ResultId
    {
        $dateFormat = $this->getDateFormatEloquentModel();

        $task = self::create(
            $newTask->getTitle(),
            $newTask->getDescription(),
            $newTask->getAuthorId(),
            $newTask->getExecutorId(),
            $newTask->getStartDate($dateFormat),
            $newTask->getEndDate($dateFormat)
        );

        $task->save();

        return new ResultId($task->getKey());
    }

    public function getDateFormatEloquentModel(): string
    {
        static $dateFormat = '';

        if ($dateFormat === '') {
            $dateFormat = new TaskEloquent()->getDateFormat(); // by model or bd
            //$dateFormat = TaskEloquent::resolveConnection()->getQueryGrammar()->getDateFormat(); // by bd
        }

        return $dateFormat;
    }

    public static function create(
        string   $title,
        string   $description,
        int      $authorId,
        int|null $executorId,
        string   $startDate,
        string   $endDate
    ): TaskEloquent
    {
        $self = new TaskEloquent();
        $self->title = $title;
        $self->description = $description;
        $self->author_id = $authorId;
        $self->executor_id = $executorId;
        $self->start_date = $startDate;
        $self->end_date = $endDate;

        return $self;
    }

    public function update(TaskDomainModel $taskUpdate): bool
    {
        $dateFormat = $this->getDateFormatEloquentModel();

        $result = TaskEloquent::query()
            ->where('id', $taskUpdate->getId())
            ->update([
                'title' => $taskUpdate->getTitle(),
                'description' => $taskUpdate->getDescription(),
                'start_date' => $taskUpdate->getStartDate($dateFormat),
                'end_date' => $taskUpdate->getEndDate($dateFormat),
            ]);

        return (bool)$result;
    }
}
