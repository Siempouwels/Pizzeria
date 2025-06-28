<?php

namespace Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $verbinding = null;

    public static function connect(): PDO
    {
        if (self::$verbinding === null) {
            $host = $_ENV['DB_HOST'] ?? 'localhost';
            $name = $_ENV['DB_NAME'] ?? 'default';
            $user = $_ENV['DB_USER'] ?? 'root';
            $pass = $_ENV['DB_PASS'] ?? '';

            $dsn = "sqlsrv:Server={$host};Database={$name};ConnectionPooling=0;TrustServerCertificate=1";

            try {
                self::$verbinding = new PDO(
                    $dsn,
                    $user,
                    $pass,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                die("❌ Verbindingsfout met database: " . $e->getMessage());
            }
        }

        return self::$verbinding;
    }
}
