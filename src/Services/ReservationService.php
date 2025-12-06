<?php

include_once __DIR__ . '/../Repositories/ReservationRepository.php';

class ReservationService
{
    private ReservationRepository $repo;

    public function __construct(ReservationRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getReservation(int $id): ?array
    {
        if ($id <= 0) return null;
        return $this->repo->findById($id);
    }

    public function listReservationsForShow(int $showId): array
    {
        return $this->repo->findByShow($showId) ?? [];
    }

    public function createReservation(array $data): int
    {
        if (empty($data['name']) || empty($data['email']) || empty($data['showId'])) {
            return 0;
        }

        $data['status'] = "pending";

        return $this->repo->create($data);
    }

    public function updateStatus(int $id, string $status): int
    {
        if (!in_array($status, ["pending", "confirmed", "cancelled"])) {
            return 0;
        }

        return $this->repo->updateStatus($id, $status);
    }
}
