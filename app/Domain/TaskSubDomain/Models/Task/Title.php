<?php

namespace App\Domain\TaskSubDomain\Models\Task;

final class Title
{

    private string $value;

    final public function __construct(string $value)
    {
        $titleValue = trim($value);

        if (empty($titleValue)) {
            throw new \InvalidArgumentException('Title cannot be empty');
        }

        $this->value = $titleValue;
    }

    final public function getString(): string
    {
        return $this->value;
    }
}
