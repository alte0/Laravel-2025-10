<?php

namespace App\Application\DTO\Task;

final readonly class GetAuthorAndExecutor
{
    public function __construct(
        private int $authorId,
        private int $executorId
    )
    {
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function getExecutorId(): int
    {
        return $this->executorId;
    }
}
