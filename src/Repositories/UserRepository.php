<?php

class UserRepository {
    private PDO $pdo;
    
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    /**
     * Find a user by email (primary identifier in schema).
     */
    public function findByEmail(string $email): ?array {
        $stmt = $this->pdo->prepare('SELECT id, email, first_name, last_name, display_name, password, role, created_at, updated_at FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Backwards-compatible alias used by existing code paths.
     */
    public function findByUsername(string $username): ?array {
        return $this->findByEmail($username);
    }

    /**
     * Find a user by id.
     */
    public function findById(int $id): ?array {
        $stmt = $this->pdo->prepare('SELECT id, email, first_name, last_name, display_name, password, role, created_at, updated_at FROM users WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Create a new user. Defaults role to 'general'.
     */
    public function create(string $email, string $hashedPassword, ?string $firstName = null, ?string $lastName = null, ?string $displayName = null, string $role = 'general'): ?array {
        $stmt = $this->pdo->prepare('INSERT INTO users (email, first_name, last_name, display_name, password, role) VALUES (:email, :first_name, :last_name, :display_name, :password, :role)');
        $ok = $stmt->execute([
            ':email' => $email,
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':display_name' => $displayName,
            ':password' => $hashedPassword,
            ':role' => $role,
        ]);
        if (!$ok) {
            return null;
        }
        $id = (int)$this->pdo->lastInsertId();
        return $this->findById($id);
    }

    /**
     * Update fields on a user. Allowed: email, password, first_name, last_name, display_name, role
     */
    public function update(int $id, array $fields): ?array {
        $allowed = ['email', 'password', 'first_name', 'last_name', 'display_name', 'role'];
        $sets = [];
        $params = [':id' => $id];
        foreach ($allowed as $key) {
            if (array_key_exists($key, $fields)) {
                $sets[] = "$key = :$key";
                $params[":" . $key] = $fields[$key];
            }
        }
        if (empty($sets)) {
            return null;
        }
        $sql = 'UPDATE users SET ' . implode(', ', $sets) . ' WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        if (!$stmt->execute($params)) {
            return null;
        }
        return $this->findById($id);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
