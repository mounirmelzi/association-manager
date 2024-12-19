<?php

require_once __DIR__ . "/Logger.php";

class Session
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;

        if (session_status() === PHP_SESSION_NONE) {
            if (!session_start()) {
                Logger::error("Unexpected error while starting the session");
            }
        }

        if (!isset($_SESSION[$this->name])) {
            $_SESSION[$this->name] = [];
        }
    }

    public function name(): string
    {
        return $this->name;
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$this->name][$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$this->name][$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$this->name][$key]);
    }

    public function delete(string $key): void
    {
        unset($_SESSION[$this->name][$key]);
    }

    public function clear(): void
    {
        unset($_SESSION[$this->name]);
        $_SESSION[$this->name] = [];
    }

    public static function clearAll(): void
    {
        if (session_status() !== PHP_SESSION_NONE) {
            session_unset();
            if (!session_destroy()) {
                Logger::error("Unexpected error while destroying the session");
            }
        }
    }
}
