<?php

namespace App\Application\DTO\Task;

final readonly class CreateTaskRequestDTO
{
    final public function __construct(
        private string   $title,
        private string   $description,
        private string   $start,
        private string   $end,
        private int      $authorId,
        private int|null $executorId
    )
    {
    }

    final public function getTitle(): string
    {
        return $this->title;
    }

    final public function getDescription(): string
    {
        return $this->description;
    }

    final public function getStart(): string
    {
        return $this->start;
    }

    final public function getEnd(): string
    {
        return $this->end;
    }

    final public function getAuthorId(): int
    {
        return $this->authorId;
    }

    final public function getExecutorId(): int|null
    {
        return $this->executorId;
    }
}
