<?php

class AuditRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO audit_logs (user_id, action, entity, entity_id, ip, details)
            VALUES (:user_id, :action, :entity, :entity_id, :ip, :details)
        ");

        $stmt->execute([
            'user_id'   => $data['user_id'],
            'action'    => $data['action'],
            'entity'    => $data['entity'],
            'entity_id' => $data['entity_id'],
            'ip'        => $_SERVER['REMOTE_ADDR'] ?? null,
            'details'   => isset($data['details']) ? json_encode($data['details']) : null
        ]);

        return (int)$this->pdo->lastInsertId();
    }
}
?>
