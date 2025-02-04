<?php

namespace App\Core;

require_once __DIR__ . "/../config/database.config.php";

use PDO;
use PDOException;
use App\Utils\Logger;

class Database
{
    private static ?self $instance = null;
    private ?PDO $pdo = null;

    private function __construct()
    {
        try {
            $dsn = DB_ENGINE . ":host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8";
            $this->pdo = new PDO(dsn: $dsn, username: DB_USERNAME, password: DB_PASSWORD);
            $this->pdo->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(attribute: PDO::ATTR_DEFAULT_FETCH_MODE, value: PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            Logger::critical(message: "Database connection failed", context: ["error" => $exception->getMessage()]);
            die('Database connection failed: ' . $exception->getMessage());
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null)
            self::$instance = new self();
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    public static function close(): void
    {
        if (self::$instance !== null) {
            self::$instance->pdo = null;
            self::$instance = null;
        }
    }
}
