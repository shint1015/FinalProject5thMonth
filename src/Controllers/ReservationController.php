<?php

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

    // reuse function
    private function sanitizeString(string $value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    private function validateInt($value): ?int
    {
        $int = filter_var($value, FILTER_VALIDATE_INT);
        return $int !== false ? $int : null;
    }

    // GET 
    public function getReservation(int $id): array
    {
        $id = $this -> validateInt($id);
        if ($id === null) {
            return [[
                'success'=> false,
                'error' => 'Invalid ID'
            ], 400];
        }
        $reservation = $this->service->getReservation($id);
        if (!$reservation) {
            return [[
                'success'=> false,
                'error' => 'Reservation not found'
            ], 404];
        }
        return [[
            'success'=> true,
            'data'=> $reservation,
            'message'=> "User's reservation"
        ], 200];
    }

    // GET showlist
    public function listByShow(int $showId): array
    {   
        $showId = $this -> validateInt($showId);
        if ($showId === null) {
            return [[
                'success'=> false,
                'error' => 'Invalid show ID'
            ], 400];
        }
        $reservations = $this->service->listReservationsForShow($showId);
        return [[
            'success'=> true,
            'data'=> $reservation,
            'message'=> "List of reservation"
        ], 200];
    }

    // POST /reservation
    public function createReservation(): array
    {   
        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data)) {
            return [[
                'success' => false,
                'error' => 'Invalid JSON'
            ], 400];
        }

        // filter sanitize
        $showId = $this->validateInt($data['show_id'] ?? null);
        $userId = $this->validateInt($data['user_id'] ?? null);
        $ticketAmount = $this->validateInt($data['ticket_amount'] ?? null);
        $duration = $this->validateInt($data['duration'] ?? null);
        $totalPrice = $this->validateInt($data['ticket_total_price'] ?? null);
        
        $status = $this->sanitizeString($data['status'] ?? '');

        if (empty($status)) {
            return [[
                'success' => false,
                'error' => 'Status is required'
            ], 400];
        }

        if (
            $showId === null ||
            $userId === null ||
            $ticketAmount === null ||
            // $duration === null ||
            $totalPrice === null
        ){
            return [[
                'success' => false,
                'error' => 'Invalid reservation data'
            ], 400];
        }

        $id = $this->service->createReservation($data);

        return [[
            'success' => true,
            'reservation_id' => $id,
            'message' => 'Reservation created'
        ], 201];
    }

    // PUT /reservation/{id}
    public function updateReservation(int $id): array
    {   
        $id = $this->validateInt($id);
        if ($id === null) {
            return [[
                'success' => false,
                'error' => 'Invalid reservation ID'
            ], 400];
        }

        $data = json_decode(file_get_contents('php://input'), true);
        var_dump($data);
        if (!is_array($data)) {
            return [[
                'success' => false,
                'error' => 'Invalid JSON'
            ], 400];
        }

        // filter sanitize
        $showId = $this->validateInt($data['show_id'] ?? null);
        $userId = $this->validateInt($data['user_id'] ?? null);
        $ticketAmount = $this->validateInt($data['ticket_amount'] ?? null);
        $duration = $this->validateInt($data['duration'] ?? null);
        $totalPrice = $this->validateInt($data['ticket_total_price'] ?? null);

        $status = $this->sanitizeString($data['status'] ?? '');

        if (empty($status)) {
            return [[
                'success' => false,
                'error' => 'Status is required'
            ], 400];
        }

        if (
            $showId === null ||
            $userId === null ||
            $ticketAmount === null ||
            // $duration === null ||
            $totalPrice === null
        ){
            return [[
                'success' => false,
                'error' => 'Invalid reservation data'
            ], 400];
        }

        $updated = $this->service->updateReservation($id, $data);

        if ($updated === 0) {
            return [[
                'success' => false,
                'error' => 'Update failed'
            ], 400];
        }

        return [[
            'success' => true,
            'message' => 'Status updated'
        ], 200];
    }

    // PUT duration
    public function updateDuration(int $id): array
    {
        $id = $this->validateInt($id);
        if ($id === null) {
            return [[
                'success' => false,
                'error' => 'Invalid reservation ID'
            ], 400];
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data)) {
            return [[
                'success' => false,
                'error' => 'Invalid JSON input'
            ], 400];
        }
        $duration = $this->validateInt($data['duration'] ?? null);

        if ($duration === null || $duration <= 0) {
            return [[
                'success' => false,
                'error' => 'Valid duration is required'
            ], 400];
        }
        
        $updated = $this->service->updateDuration($id, $data['duration']);

        if ($updated === 0) {
            return [[
                'success' => false,
                'error' => 'Update failed'
            ], 400];
        }

        return [[
            'success' => true,
            'message' => 'Duration updated'
        ], 200];
    }

    // DELETE /reservation/{id}
    public function deleteReservation(int $id): array
    {   
        $id = $this->validateInt($id);
        if ($id === null) {
            return [[
                'success' => false,
                'error' => 'Invalid reservation ID'
            ], 400];
        }

        $deleted = $this->service->deleteReservation($id);

        if ($deleted === 0) {
            return [[
                'success' => false,
                'error' => 'Delete failed'
            ], 400];
        }

        return [[
            'success' => true,
            'message' => 'Reservation deleted'
        ], 200];
    }
}
