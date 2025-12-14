<?php
function appLog(string $level, string $message, array $context = []): void
{
            $baseDir = dirname(__DIR__, 2); // /api/src -> go up to /api
    $logDir = $baseDir . '/logs';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0775, true);
    }
    $date = date('Y-m-d');
    $file = $logDir . "/app-{$date}.log";
    $timestamp = date('c');
    $entry = [
        'time' => $timestamp,
        'level' => $level,
        'message' => $message,
        'context' => $context,
    ];
    @file_put_contents($file, json_encode($entry, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND | LOCK_EX);
}
