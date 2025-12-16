<?php
require_once __DIR__ . '/../Controllers/ShowsController.php';
function ShowsRouter(string $pathInfo, string $method): array
{
    

    $controller = new ShowsController();

    
    $parts = explode('/', $pathInfo);
    $resource = $parts[0] ?? '';
    $id = $parts[1] ?? null; // optional id

    // check resource
    if ($resource !== 'shows') {
        return [['error' => 'Not Found'], 404];
    }
    // control by method and id
    switch ($method) {
        case 'GET':
            // GET /shows
            if ($id === null || $id === '') {
                return $controller->list();
            }

            // GET /shows/{id}
            if (is_numeric($id)) {
                return $controller->detail((int)$id);
            }

            return [['error' => 'Not Found'], 404];

        case 'POST':
            // POST /shows
            if ($id === null || $id === '') {
                return $controller->create();
            }

            return [['error' => 'Not Found'], 404];

        default:
            return [['error' => 'Method Not Allowed'], 405];
    }
}