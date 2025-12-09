<?php
include_once __DIR__ . '/../Repositories/UserRepository.php';

class UserService {
    private UserRepository $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Attempt to authenticate a user by username/password.
     * Returns the user row (id, username, password, ...subset) on success, or null on failure.
     */
    public function authenticate(string $username, string $password): ?array
    {
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

    /**
     * Fetch a user by username (no password check).
     */
    public function findByUsername(string $username): ?array
    {
        return $this->repo->findByUsername($username);
    }

    /**
     * Create a new user account. Returns new user row or null on failure.
     */
    public function createUser(string $username, string $password): ?array
    {
        // Basic validation
        $username = trim($username);
        if ($username === '' || $password === '') {
            return null;
        }
        // Prevent duplicate usernames
        if ($this->repo->findByUsername($username) !== null) {
            return null;
        }
        // Hash password and delegate to repository
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $this->repo->create($username, $hash);
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
}
