<?php

include_once __DIR__ . '/../Services/PaymentService.php';
include_once __DIR__ . '/../../config/database.php';

class PaymentController
{
    private PaymentService $service;

    public function __construct()
    {
        $repo = new PaymentRepository(db());
        $this->service = new PaymentService($repo);
    }

    // GET /payment/{id}
    public function showPayment(int $id): array
    {
        $payment = $this->service->getPayment($id);

        if (!$payment) {
            return [[
                'success'=> false,
                'error' => 'Payment not found'
            ], 404];
        }

        return [[
            'success'=> true,
            'data'=> $payment,
            'message'=> "User's payment"
        ], 200];
    }

    // GET /payment/list
    public function listPayments(): array
    {
        $list = $this->service->getPaymentList();
        return [[
            'success'=> true,
            'data'=> $payment,
            'message' => "List of payment"
        ], 200];
    }

    // GET /payment/reservation/{id} (optional)
    public function listPaymentsByReservation(int $reservationId): array
    {
        $list = $this->service->getPaymentsByReservation($reservationId);
        return [[
            'success' => true,
            'data' => $payment,
            'message' => "List of payment"
        ], 200];
    }

    // POST /payment
    public function createPayment(): array
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            return [[
                'success' => false,
                'error' => 'Invalid JSON'
            ], 400];
        }

        $id = $this->service->createPayment($data);

        if ($id === 0) {
            return [[
                'success' => false,
                'error' => 'Invalid payment data'
            ], 400];
        }

        return [[
            'success' => true,
            'message' => 'Payment created', 
            'payment_id' => $id
            ], 201];
    }

    // PUT /payment/{id}
    public function updatePayment(int $id): array
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            return [[
                'success' => false,
                'error' => 'Status is required'
            ], 400];
        }

        $updated = $this->service->updatePayment($id, $data);

        if ($updated === 0) {
            return [[
                'success' => false,
                'error' => 'Update failed'
            ], 400];
        }

        return [[
            'success' => true,
            'message' => 'Status updated'
        ], 200];
    }

    // DELETE /payment/{id}
    public function deletePayment(int $id): array
    {
        $deleted = $this->service->deletePayment($id);

        if ($deleted === 0) {
            return [[
                'success' => false,
                'error' => 'Delete failed'
            ], 400];
        }

        return [[
            'success' => true,
            'message' => 'Payment deleted'
        ], 200];
    }
}
