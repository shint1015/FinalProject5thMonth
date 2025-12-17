<?php
function SeatRouter(string $pathInfo, string $method): array {
    include_once __DIR__ . '/../Controllers/SeatController.php';
    $controller = new SeatController();

    // Split the route path
    $pathParts = explode('/', trim($pathInfo, '/'));
    $resource = $pathParts[0] ?? '';
    $id = $pathParts[1] ?? '';

    switch ($method) {

        // GET: seat/{id} or seat/list
        case 'GET':
            // GET /seat/{id}
            if ($resource === 'seat' && is_numeric($id)) {
                return $controller->showSeat($id);

            // GET /seat/list
            } elseif ($resource === 'seat' && $id === 'list') {
                return $controller->listSeats();
            }

            return [['error' => 'Not Found'], 404];

        // POST: seat
        case 'POST':
            // POST /seat
            if ($resource === 'seat') {
                return $controller->createSeat();
            }
            return [['error' => 'Not Found'], 404];

        // PUT: seat/{id}
        case 'PUT':
            // PUT /seat/{id}
            if ($resource === 'seat' && is_numeric($id)) {
                return $controller->updateSeat($id);
            }
            return [['error' => 'Not Found'], 404];

        // DELETE: seat/{id}
        case 'DELETE':
            if ($resource === 'seat' && is_numeric($id)) {
                return $controller->deleteSeat($id);
            }
            return [['error' => 'Not Found'], 404];

        default:
            return [['error' => 'Method Not Allowed'], 405];
    }
}
?>
