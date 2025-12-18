<?php
// src/Middlewares/AuthMiddleware.php
namespace App\Middleware;

include_once __DIR__ . '/../Helpers/jwt.php';
include_once __DIR__ . '/../../config/env.php';

class AuthMiddleware
{
    /**
     * Require authentication.
     * - Session-based: expects `$_SESSION['user']` set by login.
     * - JWT-based: expects `Authorization: Bearer <jwt>`.
     */
    public static function requireAuth(): void
    {
        // 1) Session-based auth (browser clients)
        if (session_status() !== PHP_SESSION_ACTIVE) {
            if (!headers_sent()) {
                @session_start();
            }
        }

        // 2) JWT auth (API clients)
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $secret = defined('JWT_SECRET') ? JWT_SECRET : 'change-me';
        $payload = jwt_check_bearer($authHeader, $secret, 'HS256');
        if ($payload === null) {
            // Differentiate missing header vs invalid token
            if (!preg_match('/^Bearer\s+.+$/i', trim($authHeader))) {
                self::deny('Missing or invalid Authorization header');
            }
            self::deny('Invalid or expired token');
        }

        $sessionUser = $_SESSION['user'] ?? null;
        if (is_array($sessionUser) && !empty($sessionUser['user_id'])) {
            $_SERVER['AUTH_USER_ID'] = $sessionUser['user_id'];
            $_SERVER['AUTH_USER_ROLE'] = $sessionUser['role'] ?? null;
            return;
        }

        // Context from JWT
        $_SERVER['AUTH_JWT_PAYLOAD'] = $payload;
        $_SERVER['AUTH_USER_ID'] = $payload['sub'] ?? 'jwt-user';
        $_SERVER['AUTH_USER_ROLE'] = $payload['role'] ?? null;
    }

    /**
     * Instance-style handler if used as middleware in a pipeline.
     */
    public function handle(): void
    {
        self::requireAuth();
    }

    private static function deny(string $reason, int $status = 401): void
    {
        // Log if global appLog is available
        if (function_exists('appLog')) {
            appLog('WARN', 'Auth denied', [
                'reason' => $reason,
                'endpoint' => $_SERVER['REQUEST_URI'] ?? '',
                'method' => $_SERVER['REQUEST_METHOD'] ?? '',
            ]);
        }

        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => 'Unauthorized',
            'message' => $reason,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Require an admin role. Uses session `role === 'admin'` by default.
     * Optionally supports an admin token via `API_ADMIN_TOKEN` for token-based clients.
     */
    public static function requireAdmin(): void
    {
        // Ensure authenticated first
        self::requireAuth();

        // Session-based role check
        $role = $_SESSION['user']['role'] ?? ($_SERVER['AUTH_USER_ROLE'] ?? null);
        if ($role === 'admin') {
            return;
        }

        self::deny('Admin privileges required', 403);
    }
}