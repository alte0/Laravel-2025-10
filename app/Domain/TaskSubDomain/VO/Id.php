<?php

namespace App\Domain\TaskSubDomain\VO;

final class Id
{

    private int $value;

    final public function __construct(int $value = 0)
    {
        if (1 > $value && 0 !== $value) {
            throw new \InvalidArgumentException('Executor $id must be a positive integer');
        }

        $this->value = $value;
    }

    final public function getValue(): int|null
    {
        return $this->value;
    }
}
