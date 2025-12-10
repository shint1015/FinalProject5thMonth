<?php

class SeatRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Find seat by seat_id (primary key)
    public function findById(int $seatId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM SeatTable WHERE seat_id = :seat_id
        ");
        $stmt->execute(['seat_id' => $seatId]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Get all seats for a reservation
    public function findByReservation(int $reservationId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM SeatTable WHERE reservation_id = :reservation_id
        ");
        $stmt->execute(['reservation_id' => $reservationId]);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Create a new seat record
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO SeatTable (reservation_id, seat_number, seat_price)
            VALUES (:reservation_id, :seat_number, :seat_price)
        ");

        $stmt->execute([
            'reservation_id' => $data['reservation_id'],
            'seat_number' => $data['seat_number'],
            'seat_price' => $data['seat_price']
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    // Update seat info
    public function update(int $seatId, array $data): int
    {
        $stmt = $this->pdo->prepare("
            UPDATE SeatTable 
            SET seat_number = :seat_number,
                seat_price = :seat_price
            WHERE seat_id = :seat_id
        ");

        $stmt->execute([
            'seat_number' => $data['seat_number'],
            'seat_price' => $data['seat_price'],
            'seat_id' => $seatId
        ]);

        return $stmt->rowCount();
    }

    // Delete a seat
    public function delete(int $seatId): int
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM SeatTable WHERE seat_id = :seat_id
        ");
        $stmt->execute(['seat_id' => $seatId]);

        return $stmt->rowCount();
    }
}

?>
