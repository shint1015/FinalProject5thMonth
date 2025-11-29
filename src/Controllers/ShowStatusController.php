<?php
namespace App\Controllers;

use App\Repositories\ShowStatusRepository;
use App\Services\ShowStatusService;

class ShowStatusController
{
    private ShowStatusService $service;

    public function __construct()
    {
        $repo = new ShowStatusRepository(db());
        $this->service = new ShowStatusService($repo);
    }

    public function showStatus(string $id): void
    {
        $status = $this->service->getStatus((int)$id);

        if (!$status) {
            json_response(['error' => 'Status not found'], 404);
            return;
        }
        json_response(['data' => $status]);
    }
    public function listStatuses(): void
    {
        $statuses = $this->service->getStatusList();
        json_response(['data' => $statuses]);
    }
    public function createStatus(): void
    {
        $status = htmlspecialchars($_POST['status'], ENT_QUOTES, 'UTF-8');
        $createdStatus = $this->service->createStatus($status);
        if (!$createdStatus) {
            json_response(['error' => 'Status not created'], 400);
            return;
        }
        json_response(['data' => $createdStatus], 201);
    }

    public function updateStatus(string $id): void
    {
        $status = htmlspecialchars($_REQUEST['status'] ?? "", ENT_QUOTES, 'UTF-8');

        $updateStatus = $this->service->updateStatus((int)$id, $status);
        if (!$updateStatus) {
            json_response(['error' => 'Status not found or not updated'], 404);
            return;
        }
        json_response(['message' => 'Status updated successfully']);
    }

    public function deleteStatus(string $id): void
    {
        $deleteStatus = $this->service->deleteStatus((int)$id);
        if (!$deleteStatus) {
            json_response(['error' => 'Status not found or not deleted'], 404);
            return;
        }
        json_response(['message' => 'Status deleted successfully']);
    }

}