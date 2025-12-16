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

    // reuse function

    private function sanitizeString(string $value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    private function validateInt($value): ?int
    {
        $int = filter_var($value, FILTER_VALIDATE_INT);
        return $int !== false ? $int : null;
    }

    // GET /payment/{id}
    public function showPayment(int $id): array
    {
        $id = $this->validateInt($id);
        if ($id === null) {
            return [[
                'success' => false,
                'error' => 'Invalid payment ID'
            ], 400];
        }

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

    // GET /payment/reservation/{id} 
    public function listPaymentsByReservation(int $reservationId): array
    {
        $reservationId = $this->validateInt($reservationId);
        if ($reservationId === null) {
            return [[
                'success' => false,
                'error' => 'Invalid reservation ID'
            ], 400];
        }

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

        if (!is_array($data)) {
            return [[
                'success' => false,
                'error' => 'Invalid JSON'
            ], 400];
        }
        //sanitize
        $reservationId = $this->validateInt($data['reservation_id'] ?? null);
        $status        = $this->sanitizeString($data['status'] ?? '');
        $creditNumber  = $this->sanitizeString($data['credit_number'] ?? '');
        $creditName    = $this->sanitizeString($data['credit_name'] ?? '');
        $creditExpire  = $this->sanitizeString($data['credit_expired_at'] ?? '');

        if (empty($status)) {
            return [[
                'success' => false,
                'error' => 'Status is required'
            ], 400];
        }

        if (
            $reservationId === null ||
            empty($creditNumber) ||
            empty($creditName) ||
            empty($creditExpire)
        ) {
            return [[
                'success' => false,
                'error' => 'Invalid payment data'
            ], 400];
        }

        $id = $this->service->createPayment([
            'reservation_id' => $reservationId,
            'status' => $status,
            'credit_number' => $creditNumber,
            'credit_name' => $creditName,
            'credit_expired_at' => $creditExpire
        ]);

        return [[
            'success' => true,
            'message' => 'Payment created', 
            'payment_id' => $id
            ], 201];
    }

    // PUT /payment/{id}
    public function updatePayment(int $id): array
    {
        $id = $this->validateInt($id);
        if ($id === null) {
            return [[
                'success' => false,
                'error' => 'Invalid payment ID'
            ], 400];
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data)) {
            return [[
                'success' => false,
                'error' => 'Invalid JSON'
            ], 400];
        }

        //sanitize
        $reservationId = $this->validateInt($data['reservation_id'] ?? null);
        $status        = $this->sanitizeString($data['status'] ?? '');
        $creditNumber  = $this->sanitizeString($data['credit_number'] ?? '');
        $creditName    = $this->sanitizeString($data['credit_name'] ?? '');
        $creditExpire  = $this->sanitizeString($data['credit_expired_at'] ?? '');

        if (empty($status)) {
            return [[
                'success' => false,
                'error' => 'Status is required'
            ], 400];
        }

        if (
            $reservationId === null ||
            empty($creditNumber) ||
            empty($creditName) ||
            empty($creditExpire)
        ) {
            return [[
                'success' => false,
                'error' => 'Invalid payment data'
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
            'message' => 'Payment updated'
        ], 200];
    }

    // DELETE /payment/{id}
    public function deletePayment(int $id): array
    {
        $id = $this->validateInt($id);
        if ($id === null) {
            return [[
                'success' => false,
                'error' => 'Invalid payment ID'
            ], 400];
        }

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
