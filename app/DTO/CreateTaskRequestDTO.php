<?php

namespace App\DTO;

final readonly class CreateTaskRequestDTO
{
    public function __construct(
        private string $title,
        private string $description,
        private string $start,
        private string $end,
        private int $authorId,
    )
    {
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

    public function getAuthorId(): int
    {
        return $this->authorId;
    }
}
