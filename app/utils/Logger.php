<?php

namespace App\Utils;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

class Logger
{
    private static string $logsFile = __DIR__ . '/../../logs/log.txt';
    private static ?MonologLogger $logger = null;

    private function __construct() {}

    private static function getLogger(): MonologLogger
    {
        if (self::$logger === null) {
            self::$logger = new MonologLogger('AssociationManagerLogger');
            $fileHandler = new RotatingFileHandler(self::$logsFile);
            $fileHandler->setFormatter(new LineFormatter("[%datetime%] %channel%.%level_name%: \"%message%\" %context%\n"));
            self::$logger->pushHandler($fileHandler);
        }

        return self::$logger;
    }

    public static function error(string $message, array $context = []): void
    {
        self::getLogger()->error($message, $context);
    }

    public static function info(string $message, array $context = []): void
    {
        self::getLogger()->info($message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::getLogger()->warning($message, $context);
    }

    public static function debug(string $message, array $context = []): void
    {
        self::getLogger()->debug($message, $context);
    }

    public static function critical(string $message, array $context = []): void
    {
        self::getLogger()->critical($message, $context);
    }
}
