<?php
// src/Middlewares/AuthMiddleware.php
namespace App\Middleware;

class AuthMiddleware
{
    /**
     * Require a valid Authorization header (Bearer token) and exit with 401 JSON on failure.
     */
    public static function requireAuth(): void
    {
        // 1) Session-based auth (browser clients)
        if (session_status() !== PHP_SESSION_ACTIVE) {
            if (!headers_sent()) {
                @session_start();
            }
        }
        $sessionUserId = $_SESSION['user_id'] ?? null;
        if ($sessionUserId) {
            $_SERVER['AUTH_USER_ID'] = $sessionUserId;
            return;
        }

        // 2) Bearer token auth (API clients)
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (!preg_match('/^Bearer\s+(.+)$/i', $authHeader, $matches)) {
            self::deny('Missing or invalid Authorization header');
        }
        $token = $matches[1] ?? '';
        $validToken = getenv('API_BEARER_TOKEN') ?: 'my-secret-token';
        if ($token !== $validToken) {
            self::deny('Invalid token');
        }

        // Optional: context for token users
        $_SERVER['AUTH_USER_ID'] = $_SERVER['AUTH_USER_ID'] ?? 'token-user';
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
        $role = $_SESSION['role'] ?? null;
        if ($role === 'admin') {
            return;
        }

        // Token-based admin override: if bearer matches admin token
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $adminToken = getenv('API_ADMIN_TOKEN') ?: null;
        if ($adminToken && preg_match('/^Bearer\s+(.+)$/i', $authHeader, $m)) {
            if (($m[1] ?? '') === $adminToken) {
                return;
            }
        }

        self::deny('Admin privileges required', 403);
    }
}