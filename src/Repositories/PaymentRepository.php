<?php

class PaymentRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Find payment by payment_id (primary key)
    public function findById(int $paymentId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM Payment WHERE payment_id = :payment_id
        ");
        $stmt->execute(['payment_id' => $paymentId]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Find payment by reservation_id
    public function findByReservation(int $reservationId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM Payment WHERE reservation_id = :reservation_id
        ");
        $stmt->execute(['reservation_id' => $reservationId]);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Create new payment record
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO Payment (reservation_id, status, credit_number, credit_name, credit_expired_at)
            VALUES (:reservation_id, :status, :credit_number, :credit_name, :credit_expired_at)
        ");

        $stmt->execute([
            'reservation_id' => $data['reservation_id'],
            'status' => $data['status'],
            'credit_number' => $data['credit_number'],
            'credit_name' => $data['credit_name'],
            'credit_expired_at' => $data['credit_expired_at']
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    // Update payment status 
    public function update(int $id, array $data): int
    {
        $stmt = $this->pdo->prepare("
            UPDATE Payment 
            SET status = :status,
            credit_number = :credit_number,
            credit_name = :credit_name,
            credit_expired_at = :credit_expired_at,
            reservation_id = :reservation_id
            WHERE payment_id = :id
        ");
        $stmt->execute([
            'status'             => $data['status'],
            'credit_number'      => $data['credit_number'],
            'credit_name'        => $data['credit_name'],
            'credit_expired_at'  => $data['credit_expired_at'],
            'reservation_id'     => $data['reservation_id'],
            'id'                 => $id
        ]);
        return $stmt->rowCount();
    }

    // Delete payment
    public function delete(int $paymentId): int
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM Payment WHERE payment_id = :payment_id
        ");
        $stmt->execute(['payment_id' => $paymentId]);

        return $stmt->rowCount();
    }
}

?>
