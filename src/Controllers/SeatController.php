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

    //reuse function
    private function sanitizeString(string $value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    private function validateInt($value): ?int
    {
        $int = filter_var($value, FILTER_VALIDATE_INT);
        return $int !== false ? $int : null;
    }

    // GET /seat/{id}
    public function showSeat(int $id): array
    {
        $id = $this->validateInt($id);
        if ($id === null) {
            return [[
                'success'=> false,
                'error' => 'Invalid seat ID'
            ], 400];
        }

        $seat = $this->service->getSeat($id);

        if (!$seat) {
            return [[
                'success' => false,
                'error' => "Seat not found"
            ], 404];
        }

        return [[
            'success'=> true,
            'data' => $seat,
            'message'=> "Seat details"
        ], 200];
    }

    // GET /seat/list
    public function listSeats(): array
    {
        $list = $this->service->getSeatList();

        return [[
            'success'=> true,
            'data' => $list,
            'message'=> "User's list"
        ], 200];
    }

    // GET /seat/reservation/{id}
    public function listSeatsByReservation(int $reservationId): array
    {
        $reservationId = $this->validateInt($reservationId);
        if ($reservationId === null) {
            return [[
                'success' => false,
                'error' => 'Invalid reservation ID'
            ], 400];
        }

        $list = $this->service->getSeatsByReservation($reservationId);

        return [[
            'success'=> true,
            'data' => $list,
            'message'=> "User's list"
        ], 200];
    }

    // POST /seat
    public function createSeat(): array
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data)) {
            return [[
                'success'=> false,
                'error' => 'Invalid JSON'
            ], 400];
        }
        //santisize
        $reservationId = $this->validateInt($data['reservation_id'] ?? null);
        $seatNumber    = $this->sanitizeString($data['seat_number'] ?? '');
        $seatPrice     = $this->validateInt($data['seat_price'] ?? null);
        
        if (
            $reservationId === null ||
            empty($seatNumber) ||
            $seatPrice === null ||
            $seatPrice <= 0
        ) {
            return [[
                'success'=> false,
                'error' => 'Invalid seat data'
            ], 400];
        }

        $id = $this->service->createSeat([
            'reservation_id' => $reservationId,
            'seat_number' => $seatNumber,
            'seat_price' => $seatPrice
        ]);

        return [[
            'success'=> true,
            'seat_id' => $id,
            'message' => 'Seat created'
        ], 201];
    }

    // PUT /seat/{id}
    public function updateSeat(int $id): array
    {
        $id = $this->validateInt($id);
        if ($id === null) {
            return [[
                'success'=> false,
                'error' => 'Invalid seat ID'
            ], 400];
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data)) {
            return [[
                'success'=> false,
                'error' => 'Invalid JSON'
            ], 400];
        }

        //santisize
        $reservationId = $this->validateInt($data['reservation_id'] ?? null);
        $seatNumber    = $this->sanitizeString($data['seat_number'] ?? '');
        $seatPrice     = $this->validateInt($data['seat_price'] ?? null);
        
        if (
            $reservationId === null ||
            empty($seatNumber) ||
            $seatPrice === null ||
            $seatPrice <= 0
        ) {
            return [[
                'success'=> false,
                'error' => 'Invalid seat data'
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
        $id = $this->validateInt($id);
        if ($id === null) {
            return [[
                'success'=> false,
                'error' => 'Invalid seat ID'
            ], 400];
        }

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
