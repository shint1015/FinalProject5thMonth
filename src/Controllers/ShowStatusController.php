<?php

include_once __DIR__ . '/../Repositories/ShowStatusRepository.php';
include_once __DIR__ . '/../Services/ShowStatusService.php';
include_once __DIR__ . '/../../config/database.php';

class ShowStatusController
{
    private ShowStatusService $service;

    public function __construct()
    {
        $repo = new ShowStatusRepository(db());
        $this->service = new ShowStatusService($repo);
    }

    public function showStatus(string $id): array
    {
        $status = $this->service->getStatus((int)$id);

        if (!$status) {
            return [['error' => 'Status not found'], 404];
        }
        return [['data' => $status], 200];
    }
    public function listStatuses(): array
    {
        $statuses = $this->service->getStatusList();
        if (!$statuses) {
            return [['error' => 'No statuses found'], 404];
        }
        return [['data' => $statuses],200];
    }
    public function createStatus(): array
    {
        $status = htmlspecialchars($_POST['status'], ENT_QUOTES, 'UTF-8');
        $createdStatus = $this->service->createStatus($status);
        if (!$createdStatus) {
            return [['error' => 'Status not created'], 400];
        }
        return ['data' => $createdStatus];
    }

    public function updateStatus(string $id): array
    {
        $status = htmlspecialchars($_REQUEST['status'] ?? "", ENT_QUOTES, 'UTF-8');

        $updateStatus = $this->service->updateStatus((int)$id, $status);
        if (!$updateStatus) {
            return [['error' => 'Status not found or not updated'], 404];
        }
        return ['message' => 'Status updated successfully'];
    }

    public function deleteStatus(string $id): array
    {
        $deleteStatus = $this->service->deleteStatus((int)$id);
        if (!$deleteStatus) {
            return [['error' => 'Status not found or not deleted'], 404];
        }
        return ['message' => 'Status deleted successfully'];
    }

}