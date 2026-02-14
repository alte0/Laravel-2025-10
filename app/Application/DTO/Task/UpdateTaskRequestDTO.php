<?php

namespace App\Application\DTO\Task;

final readonly class UpdateTaskRequestDTO
{
    final public function __construct(
        private int $id,
        private string $title,
        private string $description,
        private string $start,
        private string $end,
    )
    {
    }

    final public function getId(): int
    {
        return $this->id;
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
}
