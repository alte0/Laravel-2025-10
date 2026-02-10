<?php

namespace App\Http\Resources;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
         * @var Task $task
         */
        $task = $this->resource;
        $format = 'd.m.y H:i';

        return [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'authorId' => $task->author_id,
            'executorId' => $task->executor_id,
            'createdAt' => $task->created_at->format($format),
            'updatedAt' => $task->updated_at->format($format),
            'startDate' => $task->start_date->format($format),
            'endDate' => $task->end_date->format($format),
        ];
    }
}
