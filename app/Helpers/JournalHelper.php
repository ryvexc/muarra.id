<?php

namespace App\Helpers;

class JournalHelper
{
    public static function event(string $type, array $payload = []): void
    {
        self::write("event.log", "EVT", $type, $payload);
    }

    public static function system(string $level, string $message, array $context = []): void
    {
        self::write("system.log", "SYS", $level, [
            "message" => $message,
            "context" => $context,
        ]);
    }

    private static function write(string $fileName, string $prefix, string $type, array $data): void
    {
        $path = storage_path("journals");

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $file = "{$path}/{$fileName}";

        $entry = array_merge([
            "id"   => self::nextId($file, $prefix),
            "time" => now()->toISOString(),
            "type" => $type,
        ], $data);

        file_put_contents(
            $file,
            json_encode($entry, JSON_UNESCAPED_UNICODE) . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }

    private static function nextId(string $file, string $prefix): string
    {
        if (!file_exists($file) || filesize($file) === 0) {
            return "{$prefix}-000001";
        }

        $fp = fopen($file, "rb");

        fseek($fp, -1, SEEK_END);

        while (ftell($fp) > 0 && fgetc($fp) !== "\n") {
            fseek($fp, -2, SEEK_CUR);
        }

        $lastLine = trim(fgets($fp));
        fclose($fp);

        $last = json_decode($lastLine, true);

        $number = isset($last["id"])
            ? ((int) substr($last["id"], strlen($prefix) + 1)) + 1
            : 1;

        return sprintf("%s-%06d", $prefix, $number);
    }
}
