<?php

namespace App\Domain\TaskSubDomain\Models;

final class Project
{
    private int $id;
    private string $name;
    /**
     * @var Task[]
     */
    private array $taskItems = [];

    public function __construct(
        int    $id,
        string $name,
    )
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTaskItems(): array
    {
        return $this->taskItems;
    }

    public function setTaskItems(Task $task): void
    {
        $this->taskItems[$task->getId()] = $task;
    }
}
