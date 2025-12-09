<?php

class UserRepository {
    private PDO $pdo;
    
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function findByUsername(string $username): ?array {
        $stmt = $this->pdo->prepare('SELECT id, username, password FROM users WHERE username = :username LIMIT 1');
        $stmt->execute([':username' => $username]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(string $username, string $hashedPassword): ?array {
        $stmt = $this->pdo->prepare('INSERT INTO users (username, password) VALUES (:u, :p)');
        if (!$stmt->execute([':u' => $username, ':p' => $hashedPassword])) {
            return null;
        }
        $id = (int)$this->pdo->lastInsertId();
        return ['id' => $id, 'username' => $username, 'password' => $hashedPassword];
    }

    public function update(int $id, array $fields): ?array {
        $sets = [];
        $params = [':id' => $id];
        if (isset($fields['username'])) {
            $sets[] = 'username = :u';
            $params[':u'] = (string)$fields['username'];
        }
        if (isset($fields['password'])) {
            $sets[] = 'password = :p';
            $params[':p'] = (string)$fields['password'];
        }
        if (empty($sets)) {
            return null;
        }
        $sql = 'UPDATE users SET ' . implode(', ', $sets) . ' WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        if (!$stmt->execute($params)) {
            return null;
        }
        $stmt2 = $this->pdo->prepare('SELECT id, username, password FROM users WHERE id = :id');
        $stmt2->execute([':id' => $id]);
        $row = $stmt2->fetch();
        return $row ?: null;
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
