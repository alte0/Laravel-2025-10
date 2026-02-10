<?php

namespace App\Domain\TaskSubDomain\VO;

final class AuthorId
{

    private int $value;

    final public function __construct(int $value)
    {
        if ($value < 1) {
            throw new \InvalidArgumentException('Author $id must be a positive integer');
        }

        $this->value = $value;
    }

    final public function getValue(): int
    {
        return $this->value;
    }
}
