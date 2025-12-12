<?php

class CategoryRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function list(): array {
        $stmt = $this->pdo->query('SELECT category_id, category_name, sort, created_at, updated_at FROM categories ORDER BY sort ASC, category_id ASC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function find(int $id): ?array {
        $stmt = $this->pdo->prepare('SELECT category_id, category_name, sort, created_at, updated_at FROM categories WHERE category_id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(array $data): ?array {
        $stmt = $this->pdo->prepare('INSERT INTO categories (category_name, sort) VALUES (:name, :sort)');
        $ok = $stmt->execute([
            ':name' => $data['category_name'],
            ':sort' => (int)($data['sort'] ?? 0),
        ]);
        if (!$ok) return null;
        $id = (int)$this->pdo->lastInsertId();
        return $this->find($id);
    }

    public function update(int $id, array $data): ?array {
        $allowed = ['category_name','sort'];
        $sets = [];
        $params = [':id' => $id];
        foreach ($allowed as $k) {
            if (array_key_exists($k, $data)) {
                $sets[] = "$k = :$k";
                $params[":".$k] = $k === 'sort' ? (int)$data[$k] : $data[$k];
            }
        }
        if (empty($sets)) return null;
        $sql = 'UPDATE categories SET ' . implode(', ', $sets) . ' WHERE category_id = :id';
        $stmt = $this->pdo->prepare($sql);
        if (!$stmt->execute($params)) return null;
        return $this->find($id);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM categories WHERE category_id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
