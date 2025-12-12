<?php

include_once __DIR__ . '/../Services/SeatService.php';
include_once __DIR__ . '/../../config/database.php';

class SeatController
{
    private SeatService $service;

    public function __construct()
    {
        $repo = new SeatRepository(db());
        $this->service = new SeatService($repo);
    }

    // GET /seat/{id}
    public function showSeat(int $id): array
    {
        $seat = $this->service->getSeat($id);

        if (!$seat) {
            return [[
                'success'=> false,
                'error' => 'Seat not found',
            ], 404];
        }

        return [[
            'success'=> true,
            'date' => $seat,
            'message'=> "User's reservation"
        ], 200];
    }

    // GET /seat/list
    public function listSeats(): array
    {
        $list = $this->service->getSeatList();

        return [[
            'success'=> true,
            'date' => $list,
            'message'=> "User's list"
        ], 200];
    }

    // GET /seat/reservation/{id}
    public function listSeatsByReservation(int $reservationId): array
    {
        $list = $this->service->getSeatsByReservation($reservationId);

        return [[
            'success'=> true,
            'date' => $list,
            'message'=> "User's list"
        ], 200];
    }

    // POST /seat
    public function createSeat(): array
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            return [[
                'success'=> false,
                'error' => 'Invalid JSON'
            ], 400];
        }

        $id = $this->service->createSeat($data);

        if ($id === 0) {
            return [[
                'success'=> false,
                'error' => 'Invalid seat data'
            ], 400];
        }

        return [[
            'success'=> true,
            'seat_id' => $id,
            'message' => 'Seat created'
        ], 201];
    }

    // PUT /seat/{id}
    public function updateSeat(int $id): array
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            return [[
                'success'=> false,
                'error' => 'Invalid JSON'
            ], 400];
        }

        $updated = $this->service->updateSeat($id, $data);

        if ($updated === 0) {
            return [[
                'success'=> false,
                'error' => 'Update failed'
            ], 400];
        }

        return [[
            'success'=> true,
            'message' => 'Seat updated'
        ], 200];
    }

    // DELETE /seat/{id}
    public function deleteSeat(int $id): array
    {
        $deleted = $this->service->deleteSeat($id);

        if ($deleted === 0) {
            return [[
                'success'=> false,
                'error' => 'Delete failed'
            ], 400];
        }

        return [[
            'success'=> true,
            'message' => 'Seat deleted'
        ], 200];
    }
}
