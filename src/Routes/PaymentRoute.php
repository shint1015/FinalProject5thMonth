<?php
require_once __DIR__ . '/../Middlewares/AuthMiddleware.php';

use App\Middleware\AuthMiddleware;

function PaymentRouter(string $pathInfo, string $method): array {
    include_once __DIR__ . '/../Controllers/PaymentController.php';
    $controller = new PaymentController();

    $pathParts = explode('/', trim($pathInfo, '/'));
    $resource = $pathParts[0] ?? '';
    $id = $pathParts[1] ?? '';

    AuthMiddleware::requireAuth();
    switch ($method) {
        case 'GET':
            // GET /payment/{id}
            if ($resource === 'payment' && is_numeric($id)) {
                return $controller->showPayment($id);

            // GET /payment/list
            } elseif ($resource === 'payment' && $id === 'list') {
                return $controller->listPayments();

            } else {
                return [['error' => 'Not Found'], 404];
            }

        case 'POST':
            // POST /payment
            if ($resource === 'payment') {
                return $controller->createPayment();
            } else {
                return [['error' => 'Not Found'], 404];
            }

        case 'PUT':
            // PUT /payment/{id}
            if ($resource === 'payment' && is_numeric($id)) {
                return $controller->updatePayment($id);
            } else {
                return [['error' => 'Not Found'], 404];
            }

        case 'DELETE':
            // DELETE /payment/{id}
            if ($resource === 'payment' && is_numeric($id)) {
                return $controller->deletePayment($id);
            } else {
                return [['error' => 'Not Found'], 404];
            }

        default:
            return [['error' => 'Method Not Allowed'], 405];
    }
}
?>
