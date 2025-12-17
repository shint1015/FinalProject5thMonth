<?php

include_once __DIR__ . '/../Repositories/SeatRepository.php';

class SeatService
{
    private SeatRepository $repo;

    public function __construct(SeatRepository $repo)
    {
        $this->repo = $repo;
    }

    // Get a single seat by seat_id
    public function getSeat(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }
        return $this->repo->findById($id);
    }

    // Get seats belonging to a reservation
    public function getSeatsByReservation(int $reservationId): array
    {
        if ($reservationId <= 0) {
            return [];
        }
        return $this->repo->findByReservation($reservationId);
    }

    // Create a new seat
    public function createSeat(array $data): int
    {
        if (
            empty($data['reservation_id']) ||
            empty($data['seat_number']) ||
            empty($data['seat_price'])
        ) {
            return 0;
        }

        return $this->repo->createSeat([
            'reservation_id' => $data['reservation_id'],
            'seat_number'    => $data['seat_number'],
            'seat_price'     => $data['seat_price']
        ]);
    }

    // Update a seat record
    public function updateSeat(int $id, array $data): int
    {
        if ($id <= 0) {
            return 0;
        }

        if (
            empty($data['seat_number']) ||
            empty($data['seat_price'])
        ) {
            return 0;
        }

        return $this->repo->updateSeat($id, [
            'seat_number' => $data['seat_number'],
            'seat_price'  => $data['seat_price']
        ]);
    }

    // Delete a seat
    public function deleteSeat(int $id): int
    {
        if ($id <= 0) {
            return 0;
        }
        return $this->repo->delete($id);
    }
}
