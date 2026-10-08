<?php

namespace Commerce\Platform;

final class Logger
{
    public static function info(string $message, array $context = []): void
    {
        self::write('INFO', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::write('ERROR', $message, $context);
    }

    private static function write(string $level, string $message, array $context): void
    {
        $file = $_SERVER['DOCUMENT_ROOT'] . Config::LOG_FILE;
        $dir = dirname($file);

        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        file_put_contents(
            $file,
            sprintf(
                "[%s] %s %s %s%s",
                date('Y-m-d H:i:s'),
                $level,
                $message,
                $context ? json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '',
                PHP_EOL
            ),
            FILE_APPEND | LOCK_EX
        );
    }
}
