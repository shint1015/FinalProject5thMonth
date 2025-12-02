<?php

class ShowStatusRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findStatusById(int $id): ?array
    {
    
        $stmt = $this->pdo->prepare("SELECT id, `status` FROM show_status WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function findStatusList(): ?array
    {
        $stmt = $this->pdo->query("SELECT id, `status` FROM show_status");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results ?: null;
    }

    public function createStatus(string $status): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO show_status (`status`) VALUES (:status)");
        $stmt->execute(['status' => $status]);
        return (int)$this->pdo->lastInsertId();
    }

    public function updateStatus(string $status, int $id): int
    {
        $stmt = $this->pdo->prepare("UPDATE show_status SET `status` = :status WHERE id = :id");
        $stmt->execute(['status' => $status, 'id' => $id]);
        return $stmt->rowCount();
    }
    public function deleteStatusById(int $id): int
    {
        $stmt = $this->pdo->prepare("DELETE FROM show_status WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }
}