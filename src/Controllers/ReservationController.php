<?php

include_once __DIR__ . '/../Repositories/ReservationRepository.php';
include_once __DIR__ . '/../Services/ReservationService.php';
include_once __DIR__ . '/../../config/database.php';

class ReservationController
{
    private ReservationService $service;

    public function __construct()
    {
        $repo = new ReservationRepository(db());
        $this->service = new ReservationService($repo);
    }

    public function getReservation(string $id): array
    {
        $res = $this->service->getReservation((int)$id);
        if (!$res) return [['error' => 'Reservation not found'], 404];

        return [['data' => $res], 200];
    }

    public function listByShow(string $showId): array
    {
    $list = $this->service->listReservationsForShow((int)$showId);

        if (!$list) return [['error' => 'No reservations found'], 404];

        return [['data' => $list], 200];
    }
    //create
    public function create(): array
    {
        $data = [
            'showId' => (int)($_POST['showId'] ?? 0),
            'name' => htmlspecialchars($_POST['name'] ?? "", ENT_QUOTES, "UTF-8"),
            'email' => htmlspecialchars($_POST['email'] ?? "", ENT_QUOTES, "UTF-8"),
            'tickets' => (int)($_POST['tickets'] ?? 1)
        ];

        $id = $this->service->createReservation($data);

        if (!$id) return [['error' => 'Reservation not created'], 400];

        return [['data' => ['id' => $id]], 201];
    }
    //update
    public function updateStatus(string $id): array
    {
        $status = htmlspecialchars($_POST['status'] ?? "", ENT_QUOTES, 'UTF-8');

        $update = $this->service->updateStatus((int)$id, $status);

        if (!$update) return [['error' => 'Status not updated'], 400];

        return [['message' => 'Status updated successfully'], 200];
    }
    //deleate
    public function deleteReservation(string $id): array
    {
    $deleted = $this->service->delete((int)$id);

    if (!$deleted) return [['error' => 'Reservation not deleted'], 400];

    return [['message' => 'Reservation deleted successfully'], 200];
    }

}
