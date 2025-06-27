<?php

namespace Core;

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
        return isset($_SESSION['username']);
    }

    public static function isPersonnel(): bool
    {
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

    public static function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrfToken(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
