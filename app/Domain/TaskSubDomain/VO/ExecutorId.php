<?php

namespace App\Domain\TaskSubDomain\VO;

final class ExecutorId
{
    private int|null $value;

    final public function __construct(int|null $value)
    {
        if (null !== $value && $value < 0) {
            throw new \InvalidArgumentException('Executor $id must be a positive integer');
        }

        $this->value = $value;
    }

    final public function getValue(): int|null
    {
        return $this->value;
    }
}
