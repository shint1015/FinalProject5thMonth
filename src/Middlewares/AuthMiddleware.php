<?php
// src/Middleware/AuthMiddleware.php
namespace App\Middleware;

class AuthMiddleware
{
    public function handle(): void
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        // Example: "Authorization: Bearer secret-token"
        if (!preg_match('/^Bearer\s+(.+)$/i', $authHeader, $matches)) {
            $this->unauthorized('Missing or invalid Authorization header');
        }

        $token = $matches[1] ?? '';

        // For learning: use a fixed token. Later you can verify JWT or DB, etc.
        $validToken = 'my-secret-token';

        if ($token !== $validToken) {
            $this->unauthorized('Invalid token');
        }

        // If you want, you can set user info into global or server vars
        // $_SERVER['AUTH_USER_ID'] = 1;
    }

    private function unauthorized(string $reason): array
    {
        return [[
            'error'   => 'Unauthorized',
            'message' => $reason,
        ], 401];
    }
}