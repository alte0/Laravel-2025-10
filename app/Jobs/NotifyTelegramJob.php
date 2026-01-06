<?php

namespace App\Jobs;

use App\Services\NotifyTelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotifyTelegramJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $message
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(NotifyTelegramService $notifyTelegramService): void
    {
        $notifyTelegramService->sendMessage($this->message);
    }
}
