<?php

function ReservationRouter(string $pathInfo, string $method): array
{
    include_once __DIR__ . '/../Controllers/ReservationController.php';

    $controller = new ReservationController();

    $parts = explode('/', $pathInfo);
    $resource = $parts[0] ?? '';
    $id = $parts[1] ?? '';
    $extra = $parts[2] ?? '';

    switch ($method) {

        case 'GET': //GET reservation
            if ($resource === 'reservation' && is_numeric($id)) {
                return $controller->getReservation($id);
            }
            if ($resource === 'reservation' && $id === 'event' && is_numeric($extra)) {
                return $controller->listByShow($extra);
            }
            return [['error' => 'Not Found'], 404];

        case 'POST': //create
            if ($resource === 'reservation') {
                return $controller->create();
            }
            return [['error' => 'Not Found'], 404];
 
        case 'PUT': // update
            if ($resource === 'reservation' && is_numeric($id) && $extra === 'status') {
                return $controller->updateStatus($id);
            }
            return [['error' => 'Not Found'], 404];

        case 'DELETE': //remove
            if ($resource === 'reservation' && is_numeric($id)) {
                return $controller->deleteReservation($id);
            }
            return [['error' => 'Not Found'], 404];
            
        default:
            return [['error' => 'Method Not Allowed'], 405];
    }
}
