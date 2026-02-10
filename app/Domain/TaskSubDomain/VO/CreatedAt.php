<?php

namespace App\Domain\TaskSubDomain\VO;

final class CreatedAt
{

    private string $value;

    final public function __construct(string $value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('CreatedAt cannot be empty');
        }

        $this->value = $value;
    }

    final public function getValue(): string
    {
        return $this->value;
    }
}
