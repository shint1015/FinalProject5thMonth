<?php

function ReservationRouter(string $pathInfo, string $method): array
{
    include_once __DIR__ . '/../Controllers/ReservationController.php';

    $controller = new ReservationController();

    $parts = explode('/', trim($pathInfo, '/'));
    $resource = $parts[0] ?? null;
    $id       = $parts[1] ?? null;   // reservation_id
    $extra    = $parts[2] ?? null;

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
                return $controller->create();
            }
            return [['error' => 'Not Found'], 404];

        case 'PUT':

            // PUT /reservation/10/status
            if ($resource === 'reservation' && is_numeric($id) && $extra === 'status') {
                return $controller->updateStatus($id);
            }

            // PUT /reservation/10/duration  (extend or update countdown)
            if ($resource === 'reservation' && is_numeric($id) && $extra === 'duration') {
                return $controller->updateDuration($id);
            }

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
