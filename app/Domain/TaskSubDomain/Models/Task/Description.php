<?php

namespace App\Domain\TaskSubDomain\Models\Task;

final class Description
{
    private string $value;

    final public function __construct(string $value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('Description cannot be empty');
        }

        $this->value = $value;
    }

    final public function getString(): string
    {
        return $this->value;
    }
}
