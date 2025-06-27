<?php

class Database
{
    private const DB_HOST = 'database_server';
    private const DB_NAME = 'pizzeria';
    private const DB_USER = 'sa';
    private const DB_PASS = 'abc123!@#';

    private static ?PDO $verbinding = null;

    /**
     * Haal een PDO-verbinding op (singleton).
     *
     * @return PDO
     */
    public static function connect(): PDO
    {
        if (self::$verbinding === null) {
            $dsn = "sqlsrv:Server=" . self::DB_HOST . ";Database=" . self::DB_NAME . ";ConnectionPooling=0;TrustServerCertificate=1";

            try {
                self::$verbinding = new PDO($dsn, self::DB_USER, self::DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                // In productie log je deze fout
                die("❌ Verbindingsfout met database: " . $e->getMessage());
            }
        }

        return self::$verbinding;
    }
}
