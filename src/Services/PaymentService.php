<?php

include_once __DIR__ . '/../Repositories/PaymentRepository.php';

class PaymentService
{
    private PaymentRepository $repo;

    public function __construct(PaymentRepository $repo)
    {
        $this->repo = $repo;
    }

    // Get a single payment
    public function getPayment(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }
        return $this->repo->findById($id);
    }

    // List all payments
    public function getPaymentList(): array
    {
        return $this->repo->findAll(); 
    }

    // List payments by reservation_id
    public function getPaymentsByReservation(int $reservationId): array
    {
        if ($reservationId <= 0) {
            return [];
        }
        return $this->repo->findByReservation($reservationId);
    }

    // Create new payment
    public function createPayment(array $data): int
    {
        if (
            empty($data['reservation_id']) ||
            empty($data['status']) ||
            empty($data['credit_number']) ||
            empty($data['credit_name']) ||
            empty($data['credit_expired_at'])
        ) {
            return 0;
        }

        return $this->repo->create([
            'reservation_id' => $data['reservation_id'],
            'status' => $data['status'],
            'credit_number' => $data['credit_number'],
            'credit_name' => $data['credit_name'],
            'credit_expired_at' => $data['credit_expired_at'],
        ]);
    }

    // Update payment status
    public function updatePaymentStatus(int $id, string $status): int
    {
        if ($id <= 0 || empty($status)) {
            return 0;
        }
        return $this->repo->updateStatus($id, $status);
    }

    // Delete payment
    public function deletePayment(int $id): int
    {
        if ($id <= 0) {
            return 0;
        }
        return $this->repo->delete($id);
    }
}
