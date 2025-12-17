<?php
declare(strict_types=1);

class ShowsRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM shows");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM shows WHERE id = ?");
        $stmt->execute([$id]);
        $show = $stmt->fetch(PDO::FETCH_ASSOC);
        return $show ?: null;
    }

    public function insert(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO shows (title, date) VALUES (?, ?)"
        );

        $ok = $stmt->execute([
            $data['title'],
            $data['date']
        ]);

        return $ok ? (int)$this->db->lastInsertId() : 0;
    }
}
?>