<?php

namespace App\Application\DTO;

final readonly class ResultId
{
    public function __construct(
        protected int $id
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
