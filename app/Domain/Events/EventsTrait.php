<?php

namespace App\Domain\Events;

trait EventsTrait
{
    public array $recordedEvents = [];

    public function recordEvent(DomainEventInterface $event): void
    {
        $this->recordedEvents[] = $event;
    }

    public function releaseEvents(): array
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];
        return $events;
    }
}
