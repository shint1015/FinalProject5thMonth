<?php

include_once __DIR__ . '/../Services/SeatService.php';

class SeatController
{
    private SeatService $service;

    public function __construct()
    {
        $repo = new SeatRepository(Database::getInstance()->getConnection());
        $this->service = new SeatService($repo);
    }

    // GET /seat/{id}
    public function showSeat(int $id): array
    {
        $seat = $this->service->getSeat($id);

        if (!$seat) {
            return [['error' => 'Seat not found'], 404];
        }

        return [$seat, 200];
    }

    // GET /seat/list
    public function listSeats(): array
    {
        $list = $this->service->getSeatList();

        return [$list, 200];
    }

    // GET /seat/reservation/{id}
    // (You can add route later if needed)
    public function listSeatsByReservation(int $reservationId): array
    {
        $list = $this->service->getSeatsByReservation($reservationId);

        return [$list, 200];
    }

    // POST /seat
    public function createSeat(): array
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            return [['error' => 'Invalid JSON'], 400];
        }

        $id = $this->service->createSeat($data);

        if ($id === 0) {
            return [['error' => 'Invalid seat data'], 400];
        }

        return [['message' => 'Seat created', 'seat_id' => $id], 201];
    }

    // PUT /seat/{id}
    public function updateSeat(int $id): array
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            return [['error' => 'Invalid JSON'], 400];
        }

        $updated = $this->service->updateSeat($id, $data);

        if ($updated === 0) {
            return [['error' => 'Update failed'], 400];
        }

        return [['message' => 'Seat updated'], 200];
    }

    // DELETE /seat/{id}
    public function deleteSeat(int $id): array
    {
        $deleted = $this->service->deleteSeat($id);

        if ($deleted === 0) {
            return [['error' => 'Delete failed'], 400];
        }

        return [['message' => 'Seat deleted'], 200];
    }
}
