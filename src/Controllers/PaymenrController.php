<?php

include_once __DIR__ . '/../Services/PaymentService.php';

class PaymentController
{
    private PaymentService $service;

    public function __construct()
    {
        $repo = new PaymentRepository(Database::getInstance()->getConnection());
        $this->service = new PaymentService($repo);
    }

    // GET /payment/{id}
    public function showPayment(int $id): array
    {
        $payment = $this->service->getPayment($id);

        if (!$payment) {
            return [['error' => 'Payment not found'], 404];
        }

        return [$payment, 200];
    }

    // GET /payment/list
    public function listPayments(): array
    {
        $list = $this->service->getPaymentList();
        return [$list, 200];
    }

    // GET /payment/reservation/{id} (optional)
    public function listPaymentsByReservation(int $reservationId): array
    {
        $list = $this->service->getPaymentsByReservation($reservationId);
        return [$list, 200];
    }

    // POST /payment
    public function createPayment(): array
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            return [['error' => 'Invalid JSON'], 400];
        }

        $id = $this->service->createPayment($data);

        if ($id === 0) {
            return [['error' => 'Invalid payment data'], 400];
        }

        return [['message' => 'Payment created', 'payment_id' => $id], 201];
    }

    // PUT /payment/{id}
    public function updatePayment(int $id): array
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            return [['error' => 'Invalid JSON'], 400];
        }

        $updated = $this->service->updatePaymentStatus($id, $data['status'] ?? '');

        if ($updated === 0) {
            return [['error' => 'Update failed'], 400];
        }

        return [['message' => 'Payment updated'], 200];
    }

    // DELETE /payment/{id}
    public function deletePayment(int $id): array
    {
        $deleted = $this->service->deletePayment($id);

        if ($deleted === 0) {
            return [['error' => 'Delete failed'], 400];
        }

        return [['message' => 'Payment deleted'], 200];
    }
}
