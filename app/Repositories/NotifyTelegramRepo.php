<?php

namespace App\Repositories;

use Monolog\Handler\Curl;

/**
 * Для быстрой реализации часть скопировано из монолога
 */
class NotifyTelegramRepo
{

    protected string $apiKey;

    protected string $channel;

    private const BOT_API = 'https://api.telegram.org/bot';

    public function __construct()
    {
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function setChannel(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    public function send(string $message): void
    {
        $this->sendCurl($message);
    }

    protected function sendCurl(string $message): void
    {
        $ch = curl_init();

        $url = self::BOT_API . $this->apiKey . '/SendMessage';

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $params = [
            'text' => $message,
            'chat_id' => $this->channel,
        ];

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

        $result = Curl\Util::execute($ch);

        if (!\is_string($result)) {
            throw new RuntimeException('Telegram API error. Description: No response');
        }

        $result = json_decode($result, true);

        if ($result['ok'] === false) {
            throw new RuntimeException('Telegram API error. Description: ' . $result['description']);
        }
    }
}
