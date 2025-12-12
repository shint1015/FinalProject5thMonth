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

        return $this->repo->create($data);
    }

    //count down
    public function updateDuration(int $id, string $duration): int
    {
    if ($id <= 0 || empty($duration)) return 0;
    return $this->repo->updateDuration($id, $duration);
    }

    // Update reservation status
    public function updateReservation(int $id, array $data): int
    {
        $allowed = ['pending', 'confirmed', 'cancelled'];

        foreach($data as $key => &$value){
            $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            if ($key == "show_id" || $key == "ticket_amount" || $key == "ticket_total_price") {
                if (!is_numeric($value)) {
                    return 0;
                }
            } else if ($key == "status" && !in_array($value, $allowed)) {
                return 0;
            }
        }

        if ($id <= 0) {
            return 0;
        }

        return $this->repo->update($id, $data);
    }

    // Delete reservation
    public function deleteReservation(int $id): int
    {
        if ($id <= 0) return 0;
        return $this->repo->delete($id);
    }
}
