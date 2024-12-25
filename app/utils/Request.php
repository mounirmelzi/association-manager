<?php

namespace App\Utils;

class Request
{
    public static function header(string $key, $default = null): mixed
    {
        $headers = getallheaders();
        return $headers[$key] ?? $default;
    }

    public static function data(string $key, $default = null): mixed
    {
        $data = match (self::method()) {
            "GET" => $_GET,
            "POST" => $_POST,
            default => [],
        };

        return $data[$key] ?? $default;
    }

    public static function file(string $key): mixed
    {
        if (isset($_FILES[$key]) && ($_FILES[$key]["error"] !== UPLOAD_ERR_NO_FILE)) {
            return $_FILES[$key];
        }

        return null;
    }

    public static function ajax(): bool
    {
        return self::header("X-Requested-With") === "XMLHttpRequest";
    }

    public static function url(): string
    {
        return $_GET["url"] ?? "/";
    }

    public static function method(): string
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    public static function ip(): string
    {
        return $_SERVER["REMOTE_ADDR"];
    }
}
