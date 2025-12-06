<?php

include_once __DIR__ . '/../Repositories/ShowStatusRepository.php';

class ShowStatusService
{
    private ShowStatusRepository $repo;

    public function __construct(ShowStatusRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getStatus(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }
        return $this->repo->findStatusById($id);
    }

    public function getStatusList(): array
    {
        // Placeholder for future implementation
        return $this->repo->findStatusList();
    }

    public function createStatus(string $status): int
    {
        if (empty($status)) {
            return 0;
        }
        return $this->repo->createStatus($status);
    }

    public function updateStatus(int $id, int $status): int
    {
        if ($id <= 0 || empty($status)) {
            return 0;
        }
        return $this->repo->updateStatus($status, $id);
    }
    public function deleteStatus(int $id): int
    {
        if ($id <= 0) {
            return 0;
        }
        return $this->repo->deleteStatusById($id);
    }
    public function delete(int $id): int
    {
        if ($id <= 0) return 0;
        return $this->repo->delete($id);
    }
}