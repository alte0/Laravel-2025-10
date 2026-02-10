<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Application\DTO\ResultId;
use App\Application\DTO\Task\GetAuthorAndExecutor;
use App\Domain\TaskSubDomain\Models\Task as TaskDomainModel;
use App\Domain\TaskSubDomain\Repositories\TaskRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string title
 * @property string description
 * @property positive-int author_id
 * @property positive-int|null executor_id
 * @property Carbon start_date
 * @property Carbon end_date
 * @property positive-int id
 */
class Task extends Model implements TaskRepositoryInterface
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'author_id',
        'executor_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * @param array $attributes
     *
     * @deprecated use static method create()
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function fetchAuthorIdAndExecutorId(int $taskId): GetAuthorAndExecutor
    {
        $taskData = self::query()->find($taskId, ['author_id', 'executor_id'])->toArray();
        $authorAndExecutor = new GetAuthorAndExecutor(intval($taskData['author_id']), intval($taskData['executor_id']));

        return $authorAndExecutor;
    }

    public function saveModel(TaskDomainModel $newTask): ResultId
    {
        $task = self::query()->create([
            'title' => $newTask->getTitle(),
            'description' => $newTask->getDescription(),
            'start_date' => $newTask->getStartDate(),
            'end_date' => $newTask->getEndDate(),
            'author_id' => $newTask->getAuthorId(),
            'executor_id' => $newTask->getExecutorId(),
        ]);

        return new ResultId($task->getKey());
    }

    public static function create(
        string $title,
        string $description,
        int    $authorId,
        int    $executorId,
        string $startDate,
        string $endDate
    ): self
    {
        $self = new self();
        $self->title = $title;
        $self->description = $description;
        $self->author_id = $authorId;
        $self->executor_id = $executorId;
        $self->start_date = $startDate;
        $self->end_date = $endDate;

        return $self;
    }

    public function updateModel(TaskDomainModel $taskUpdate): bool
    {
        $result = self::query()
            ->where('id', $taskUpdate->getId())
            ->update([
                'title' => $taskUpdate->getTitle(),
                'description' => $taskUpdate->getDescription(),
                'start_date' => $taskUpdate->getStartDate(),
                'end_date' => $taskUpdate->getEndDate(),
            ]);

        return (bool)$result;
    }
}
