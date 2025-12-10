<?php
include_once __DIR__ . '/../Repositories/UserRepository.php';
include_once __DIR__ . '/../Helpers/jwt.php';
include_once __DIR__ . '/../../config/env.php';

class UserService {
    private UserRepository $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Attempt to authenticate a user by username/email and password.
     * Returns the user row on success, or null on failure.
     */
    public function authenticate(string $username, string $password): ?array
    {
        // Username here is treated as email per current schema
        $user = $this->repo->findByUsername($username);
        if ($user === null) {
            return null;
        }
        $stored = (string)($user['password'] ?? '');
        $ok = false;
        if (password_get_info($stored)['algo'] !== 0) {
            $ok = password_verify($password, $stored);
        } else {
            $ok = hash_equals($stored, $password);
        }
        if (!$ok) {
            return null;
        }
        return $user;
    }

    public function isAdmin(array $user) : bool{
        $role = strtolower((string)($user['role'] ?? ''));
        return $role === 'admin';
    }

    /**
     * Fetch a user by username (no password check).
     */
    public function findByUsername(string $username): ?array
    {
        return $this->repo->findByUsername($username);
    }

    public function findByEmail(string $email): ?array
    {
        return $this->repo->findByEmail($email);
    }

    /**
     * Create a new user account. Returns new user row or null on failure.
     */
    public function createUser(string $email, string $password, ?string $firstName = null, ?string $lastName = null, ?string $displayName = null, string $role = 'general'): ?array
    {
        // Basic validation
        $email = trim($email);
        if ($email === '' || $password === '') {
            return null;
        }
        // Prevent duplicate emails
        if ($this->repo->findByEmail($email) !== null) {
            return null;
        }
        // Hash password and delegate to repository
        $hash = password_hash($password, PASSWORD_DEFAULT);
        // Normalize role
        $roleNorm = strtolower($role);
        if (!in_array($roleNorm, ['admin', 'general'], true)) {
            $roleNorm = 'general';
        }
        return $this->repo->create($email, $hash, $firstName, $lastName, $displayName, $roleNorm);
    }

    /**
     * Update user fields. Allowed keys: username, password.
     * Returns updated user row or null if not found/failure.
     */
    public function updateUser(int $id, array $fields): ?array
    {
        // Sanitize and hash if provided, delegate to repository
        $update = [];
        if (isset($fields['username'])) {
            $username = trim((string)$fields['username']);
            if ($username === '') return null;
            $update['username'] = $username;
        }
        if (isset($fields['password'])) {
            $password = (string)$fields['password'];
            if ($password === '') return null;
            $update['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        if (empty($update)) {
            return null;
        }
        return $this->repo->update($id, $update);
    }

    /**
     * Delete user by id. Returns true on success.
     */
    public function deleteUser(int $id): bool
    {
        return $this->repo->delete($id);
    }

    /**
     * Decode a JWT (Authorization header or raw token) and fetch the user.
     * Tries sub (id) first, then email/username in payload. Returns user without password.
     */
    public function getUserFromToken(string $tokenOrHeader): ?array
    {
        $token = trim($tokenOrHeader);
        if (stripos($token, 'Bearer ') === 0) {
            $token = trim(substr($token, 7));
        }

        $secret = defined('JWT_SECRET') ? JWT_SECRET : 'change-me';
        $payload = jwt_decode($token, $secret, 'HS256');
        if ($payload === null) {
            return null;
        }

        $user = null;
        if (isset($payload['sub']) && is_numeric($payload['sub'])) {
            $user = $this->repo->findById((int)$payload['sub']);
        }
        if ($user === null && isset($payload['email'])) {
            $user = $this->repo->findByEmail((string)$payload['email']);
        }
        if ($user === null && isset($payload['username'])) {
            // Backward-compat: username field carries email in current design
            $user = $this->repo->findByEmail((string)$payload['username']);
        }
        if ($user === null) {
            return null;
        }
        // Remove sensitive info
        unset($user['password']);
        return $user;
    }

    /** Convenience: role check by id */
    public function isAdminById(int $id): bool
    {
        $u = $this->repo->findById($id);
        return $u ? $this->isAdmin($u) : false;
    }

    /** Convenience: role check by email */
    public function isAdminByEmail(string $email): bool
    {
        $u = $this->repo->findByEmail($email);
        return $u ? $this->isAdmin($u) : false;
    }
}
