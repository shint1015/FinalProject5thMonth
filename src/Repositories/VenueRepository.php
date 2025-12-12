<?php

class VenueRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function list(): array {
        $stmt = $this->pdo->query('SELECT id, name, capacity, seat_id_format, notes, layout, created_at, updated_at FROM venues ORDER BY id DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function find(int $id): ?array {
        $stmt = $this->pdo->prepare('SELECT id, name, capacity, seat_id_format, notes, layout, created_at, updated_at FROM venues WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(array $data): ?array {
        $stmt = $this->pdo->prepare('INSERT INTO venues (`name`, capacity, seat_id_format, notes, layout, `address`) VALUES (:name, :capacity, :seat_id_format, :notes, :layout, :address)');
        $ok = $stmt->execute([
            ':name' => $data['name'],
            ':capacity' => (int)$data['capacity'],
            ':seat_id_format' => $data['seat_id_format'] ?? null,
            ':notes' => $data['notes'] ?? null,
            ':address' => $data['address'] ?? null,
            ':layout' => $data['layout'] ?? null,
        ]);
        if (!$ok) return null;
        $id = (int)$this->pdo->lastInsertId();
        return $this->find($id);
    }

    public function update(int $id, array $data): ?array {
        $allowed = ['name','capacity','seat_id_format','notes','layout', 'address'];
        $sets = [];
        $params = [':id' => $id];
        foreach ($allowed as $k) {
            if (array_key_exists($k, $data)) {
                $sets[] = "$k = :$k";
                $params[":".$k] = $k === 'capacity' ? (int)$data[$k] : $data[$k];
            }
        }
        if (empty($sets)) return null;
        $sql = 'UPDATE venues SET ' . implode(', ', $sets) . ' WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        if (!$stmt->execute($params)) return null;
        return $this->find($id);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM venues WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
