<?php

class AuditService
{
    private AuditRepository $repo;

    public function __construct(AuditRepository $repo)
    {
        $this->repo = $repo;
    }

    public function log(int $userId, string $action, string $entity, int $entityId, ?array $details = null): void
    {
        $this->repo->create([
            'user_id' => $userId,
            'action' => $action,
            'entity' => $entity,
            'entity_id' => $entityId,
            'details' => $details
        ]);
    }
}
?>
