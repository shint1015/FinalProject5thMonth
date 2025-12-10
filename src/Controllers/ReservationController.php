<?php

include_once __DIR__ . '/../Services/ReservationService.php';

class ReservationController
{
    private ReservationService $service;

    public function __construct()
    {
        $repo = new ReservationRepository(Database::getInstance()->getConnection());
        $this->service = new ReservationService($repo);
    }

    // GET /reservation/{id}
    public function getReservation(int $id): array
    {
        $reservation = $this->service->getReservation($id);
        if (!$reservation) {
            return [['error' => 'Reservation not found'], 404];
        }
        return [$reservation, 200];
    }

    // GET /reservation/show/{show_id}
    public function listByShow(int $showId): array
    {
        $reservations = $this->service->listReservationsForShow($showId);
        return [$reservations, 200];
    }

    // POST /reservation
    public function create(): array
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            return [['error' => 'Invalid JSON'], 400];
        }

        $id = $this->service->createReservation($data);

        if ($id === 0) {
            return [['error' => 'Invalid reservation data'], 400];
        }

        return [['message' => 'Reservation created', 'reservation_id' => $id], 201];
    }

    // PUT /reservation/{id}/status
    public function updateStatus(int $id): array
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || empty($data['status'])) {
            return [['error' => 'Status is required'], 400];
        }

        $updated = $this->service->updateStatus($id, $data['status']);

        if ($updated === 0) {
            return [['error' => 'Update failed'], 400];
        }

        return [['message' => 'Status updated'], 200];
    }

    // PUT /reservation/{id}/duration
    public function updateDuration(int $id): array
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || empty($data['duration'])) {
            return [['error' => 'Duration is required'], 400];
        }

        $updated = $this->service->updateDuration($id, $data['duration']);

        if ($updated === 0) {
            return [['error' => 'Update failed'], 400];
        }

        return [['message' => 'Duration updated'], 200];
    }

    // DELETE /reservation/{id}
    public function deleteReservation(int $id): array
    {
        $deleted = $this->service->deleteReservation($id);

        if ($deleted === 0) {
            return [['error' => 'Delete failed'], 400];
        }

        return [['message' => 'Reservation deleted'], 200];
    }
}
