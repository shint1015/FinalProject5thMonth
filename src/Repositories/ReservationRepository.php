<?php

class ReservationRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Get reservation by reservation_id
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM Reservation WHERE reservation_id = :id
        ");
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Get reservations by show_id
    public function findByShow(int $showId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM Reservation WHERE show_id = :show_id
        ");
        $stmt->execute(['show_id' => $showId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create a reservation
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO Reservation (
                show_id, 
                user_id, 
                status, 
                ticket_amount, 
                ticket_total_price,
                duration
            ) VALUES (
                :show_id,
                :user_id,
                :status,
                :ticket_amount,
                :ticket_total_price,
                :duration
            )
        ");

        $stmt->execute([
            'show_id'            => $data['show_id'],
            'user_id'            => $data['user_id'],
            'status'             => $data['status'],
            'ticket_amount'      => $data['ticket_amount'],
            'ticket_total_price' => $data['ticket_total_price'],
            'duration'           => $data['duration']   // count down time
        ]);

        return (int)$this->pdo->lastInsertId();
    }


    // Update reservation status
    public function update(int $id, array $data): int
    {
        $stmt = $this->pdo->prepare("
            UPDATE Reservation 
            SET status = :status,
            show_id = :show_id,
            user_id = :user_id,
            ticket_amount = :ticket_amount,
            ticket_total_price = :ticket_total_price,
            duration = :duration
            WHERE reservation_id = :id
        ");
        $stmt->execute([
            'status'             => $data['status'],
            'show_id'            => $data['show_id'],
            'user_id'            => $data['user_id'],
            'ticket_amount'      => $data['ticket_amount'],
            'ticket_total_price' => $data['ticket_total_price'],
            'duration'           => $data['duration'],
            'id'     => $id
        ]);
        return $stmt->rowCount();
    }

    // Delete reservation
    public function delete(int $id): int
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM Reservation WHERE reservation_id = :id
        ");
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount();
    }
}

?>
