<?php

class Auth
{
    public static function ensureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function isLoggedIn(): bool
    {
        self::ensureSession();
        return isset($_SESSION['username']);
    }

    public static function isPersonnel(): bool
    {
        self::ensureSession();
        return isset($_SESSION['role']) && $_SESSION['role'] === 'Personnel';
    }

    public static function requireLogin(): void
    {
        if (! self::isLoggedIn()) {
            http_response_code(403);
            echo "403 - Je moet ingelogd zijn om deze pagina te bekijken.";
            exit;
        }
    }

    public static function requirePersonnel(): void
    {
        if (! self::isPersonnel()) {
            http_response_code(403);
            echo "403 - Alleen personeel heeft toegang tot deze pagina.";
            exit;
        }
    }

    public static function user(): ?array
    {
        self::ensureSession();

        if (! self::isLoggedIn()) {
            return null;
        }

        return [
            'username' => $_SESSION['username'] ?? '',
            'role' => $_SESSION['role'] ?? '',
            'first_name' => $_SESSION['first_name'] ?? '',
            'last_name' => $_SESSION['last_name'] ?? '',
            'address' => $_SESSION['address'] ?? '',
        ];
    }
}
