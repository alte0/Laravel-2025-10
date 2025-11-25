<?php

namespace App\DTO;

final readonly class UpdateTaskRequestDTO
{
    public function __construct(
        private int $id,
        private string $title,
        private string $description,
        private string $start,
        private string $end,
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStart(): string
    {
        return $this->start;
    }

    public function getEnd(): string
    {
        return $this->end;
    }
}
