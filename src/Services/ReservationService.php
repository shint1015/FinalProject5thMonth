<?php

include_once __DIR__ . '/../Repositories/ReservationRepository.php';

class ReservationService
{
    private ReservationRepository $repo;

    public function __construct(ReservationRepository $repo)
    {
        $this->repo = $repo;
    }

    // Get reservation by reservation_id
    public function getReservation(int $id): ?array
    {
        if ($id <= 0) return null;
        return $this->repo->findById($id);
    }

    // List all reservations for a show_id
    public function listReservationsForShow(int $showId): array
    {
        if ($showId <= 0) return [];
        return $this->repo->findByShow($showId);
    }

    // Create new reservation
    public function createReservation(array $data): int
    {
        // Validate required fields
        if (
            empty($data['show_id']) ||
            empty($data['user_id']) ||
            empty($data['ticket_amount']) ||
            empty($data['ticket_total_price'])
        ) {
            return 0;
        }

        // Default status = pending
        $data['status'] = $data['status'] ?? 'pending';

        // Optional: set duration to 10 minutes from now if not provided
        $data['duration'] = $data['duration'] ?? date('Y-m-d H:i:s', strtotime('+10 minutes'));

        return $this->repo->create($data);
    }

    // Update reservation status
    public function updateStatus(int $id, string $status): int
    {
        $allowed = ['pending', 'confirmed', 'cancelled'];
        if ($id <= 0 || !in_array($status, $allowed)) {
            return 0;
        }

        return $this->repo->updateStatus($id, $status);
    }

    // Optional: update duration manually
    public function updateDuration(int $id, string $newDuration): int
    {
        if ($id <= 0 || empty($newDuration)) {
            return 0;
        }

        // You would need to implement updateDuration() in ReservationRepository
        return $this->repo->updateDuration($id, $newDuration);
    }

    // Delete reservation
    public function deleteReservation(int $id): int
    {
        if ($id <= 0) return 0;
        return $this->repo->delete($id);
    }
}
