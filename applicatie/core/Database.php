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
            $host = getenv('DB_HOST') ?: 'localhost';
            $name = getenv('DB_NAME') ?: 'default';
            $user = getenv('DB_USER') ?: 'root';
            $pass = getenv('SA_PASSWORD') ?: '';

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

                self::$verbinding->exec('SET ROWCOUNT 50');
            } catch (PDOException $e) {
                die("❌ Verbindingsfout met database: " . $e->getMessage());
            }
        }

        return self::$verbinding;
    }
}
