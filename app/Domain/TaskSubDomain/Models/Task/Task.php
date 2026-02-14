<?php

namespace App\Domain\TaskSubDomain\Models\Task;

use DateTimeImmutable;

final class Task
{
    private Id $id;
    private Title $title;
    private Description $description;
    private AuthorId $author;
    private ExecutorId $executor;
    private DateTimeImmutable $startDate;
    private DateTimeImmutable $endDate;

    public function __construct(
        Id                $id,
        Title             $title,
        Description       $description,
        AuthorId          $author,
        ExecutorId        $executor,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->author = $author;
        $this->executor = $executor;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getId(): int
    {
        return $this->id->toInt();
    }

    public function getTitle(): string
    {
        return $this->title->getString();
    }

    public function getDescription(): string
    {
        return $this->description->getString();
    }

    public function getAuthorId(): int
    {
        return $this->author->toInt();
    }

    public function getExecutorId(): int|null
    {
        return $this->executor->toInt();
    }

    public function getStartDate(string $dateFormat): string
    {
        return $this->startDate->format($dateFormat);
    }

    public function getEndDate(string $dateFormat): string
    {
        return $this->endDate->format($dateFormat);
    }
}
