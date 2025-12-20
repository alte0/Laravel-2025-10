<?php

namespace App\Logging;

use Exception;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\FallbackGroupHandler;
use Monolog\Handler\TelegramBotHandler;
use Monolog\Level;
use Monolog\Logger;

class TelegramLoggerWithFallback
{
    public function __invoke(array $config): Logger
    {
        $handlers = [];
        $singleLogger = Log::channel('single')->getLogger();

        try {
            $handlers[] = new TelegramBotHandler(
                (string)$config['telegramLogger']['apiKey'],
                (string)$config['telegramLogger']['channel'],
                Level::Error,
            );
        } catch (Exception $e) {
            $singleLogger->warning($e->getMessage(), ['trace' => $e->getTrace()]);
        }

        $handlers[] = $singleLogger->getHandlers()[0];

        return new Logger(
            'appLogger',
            [
                new FallbackGroupHandler(
                    $handlers
                )
            ]
        );
    }
}
