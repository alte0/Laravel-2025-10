<?php

namespace App\Services;

use App\Repositories\NotifyTelegramRepo;
use Illuminate\Support\Facades\Config;

class NotifyTelegramService
{
    protected string $apiKey;

    protected string $channel;

    private NotifyTelegramRepo $notifyTelegramRepo;

    public function __construct(
        NotifyTelegramRepo $notifyTelegramRepo
    )
    {
        $this->apiKey = Config::get('services.notifyTelegram.apiKey', '');
        $this->channel = Config::get('services.notifyTelegram.channel', 0);

        $this->notifyTelegramRepo = $notifyTelegramRepo;
    }

    public function sendMessage(string $message): void
    {
        $this->notifyTelegramRepo
            ->setApiKey($this->apiKey)
            ->setChannel($this->channel)
            ->send($message);
    }
}
