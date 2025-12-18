<?php

class VenueRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function list(): array {
        $stmt = $this->pdo->query('SELECT venue_id, name, capacity, seat_id_format, notes, layout, created_at, updated_at FROM venues ORDER BY venue_id DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function find(int $venue_id): ?array {
        $stmt = $this->pdo->prepare('SELECT venue_id, name, capacity, seat_id_format, notes, layout, created_at, updated_at FROM venues WHERE venue_id = :venue_id');
        $stmt->execute([':venue_id' => $venue_id]);
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
        $venue_id = (int)$this->pdo->lastInsertId();
        return $this->find($venue_id);
    }

    public function update(int $venue_id, array $data): ?array {
        $allowed = ['name','capacity','seat_id_format','notes','layout', 'address'];
        $sets = [];
        $params = [':venue_id' => $venue_id];
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
        return $this->find($venue_id);
    }

    public function delete(int $venue_id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM venues WHERE venue_id = :venue_id');
        return $stmt->execute([':venue_id' => $venue_id]);
    }
}
