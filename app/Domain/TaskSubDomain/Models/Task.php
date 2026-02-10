<?php

namespace App\Domain\TaskSubDomain\Models;

use App\Domain\TaskSubDomain\Models\Project;
use App\Domain\TaskSubDomain\VO\AuthorId;
use App\Domain\TaskSubDomain\VO\Description;
use App\Domain\TaskSubDomain\VO\EndDate;
use App\Domain\TaskSubDomain\VO\ExecutorId;
use App\Domain\TaskSubDomain\VO\Id;
use App\Domain\TaskSubDomain\VO\StartDate;
use App\Domain\TaskSubDomain\VO\Title;

final class Task
{
    private int|null $id;
    private string $title;
    private string $description;
    private int $author;
    private int|null $executor;
    private string $startDate;
    private string $endDate;

    public function __construct(
        Id        $id, Title $title, Description $description, AuthorId $author, ExecutorId $executor,
        StartDate $startDate, EndDate $endDate
    )
    {
        $this->id = $id->getValue();
        $this->title = $title->getValue();
        $this->description = $description->getValue();
        $this->author = $author->getValue();
        $this->executor = $executor->getValue();
        $this->startDate = $startDate->getValue();
        $this->endDate = $endDate->getValue();
    }

    public function getId(): int|null
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

    public function getAuthorId(): int
    {
        return $this->author;
    }

    public function getExecutorId(): int|null
    {
        return $this->executor;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function getEndDate(): string
    {
        return $this->endDate;
    }
}
