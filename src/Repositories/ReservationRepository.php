<?php

class ReservationRepository 
{
    private PDO $pdo;
    
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM ReservationTable WHERE id = :id
        ");
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function findByShow(int $showId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM ReservationTable WHERE showId = :showId
        ");
        $stmt->execute(['showId' => $showId]);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO ReservationTable (showId, name, email, tickets, status)
            VALUES (:showId, :name, :email, :tickets, :status)
        ");

        $stmt->execute([
            'showId' => $data['showId'],
            'name' => $data['name'],
            'email' => $data['email'],
            'tickets' => $data['tickets'],
            'status' => $data['status']
        ]);

        return (int)$this->pdo->lastInsertId();
    }
    
    public function updateStatus(int $id, string $status): int
    {
        $stmt = $this->pdo->prepare("
            UPDATE ReservationTable SET status = :status WHERE id = :id
        ");
        $stmt->execute(['status' => $status, 'id' => $id]);

        return $stmt->rowCount();
    }

    public function delete(int $id): int
    {
        $stmt = $this->pdo->prepare("DELETE FROM ReservationTable WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }

}

?>