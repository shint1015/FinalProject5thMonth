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
    public function updatePayment(int $id, array $data): int
    {
        $allowed = ['pending', 'confirmed', 'cancelled'];

        foreach($data as $key => &$value){
            $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            if ($key == "reservation_id" || $key == "credit_number") {
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

    // Delete payment
    public function deletePayment(int $id): int
    {
        if ($id <= 0) {
            return 0;
        }
        return $this->repo->delete($id);
    }
}
