<?php
require_once __DIR__ . '/../Middlewares/AuthMiddleware.php';

use App\Middleware\AuthMiddleware;

function ReservationRouter(string $pathInfo, string $method): array
{
    include_once __DIR__ . '/../Controllers/ReservationController.php';

    $controller = new ReservationController();

    $parts = explode('/', trim($pathInfo, '/'));
    $resource = $parts[0] ?? null; // reservation
    $id = $parts[1] ?? null; // reservation_id || show
    $extra = $parts[2] ?? null; // show_id
    AuthMiddleware::requireAuth();
    switch ($method) {
        case 'GET':
            // GET /reservation/10
            if ($resource === 'reservation' && is_numeric($id)) {
                return $controller->getReservation($id);
            }

            // GET /reservation/show/5  (get all reservations by show_id)
            if ($resource === 'reservation' && $id === 'show' && is_numeric($extra)) {
                return $controller->listByShow($extra);
            }

            return [['error' => 'Not Found'], 404];

        case 'POST':
            // POST /reservation
            if ($resource === 'reservation') {
                return $controller->createReservation();
            }
            return [['error' => 'Not Found'], 404];

        case 'PUT':

            // PUT /reservation/10
            if ($resource === 'reservation' && is_numeric($id)) {
                return $controller->updateReservation($id);
            }

            // // PUT /reservation/10/duration
            // if ($resource === 'reservation' && is_numeric($id) && $extra === 'duration') {
            //     return $controller->updateDuration($id);
            // }

            return [['error' => 'Not Found'], 404];

        case 'DELETE':
            // DELETE /reservation/10
            if ($resource === 'reservation' && is_numeric($id)) {
                return $controller->deleteReservation($id);
            }
            return [['error' => 'Not Found'], 404];


        default:
            return [['error' => 'Method Not Allowed'], 405];
    }
}
