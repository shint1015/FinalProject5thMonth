<?php

require_once __DIR__ . '/../Middlewares/AuthMiddleware.php';
use App\Middleware\AuthMiddleware;

function ShowStatusRouter(string $pathInfo, string $method):array {
    include_once __DIR__ . '/../Controllers/ShowStatusController.php';
    $controller = new ShowStatusController();

    $pathParts = explode('/', $pathInfo);
    $resource = $pathParts[0] ?? '';
    $id = $pathParts[1] ?? '';
    $protectedMethods = ['POST', 'PUT', 'DELETE'];
    if (in_array($method, $protectedMethods, true)) {
        AuthMiddleware::requireAdmin();
    }

    switch ($method) {
        case 'GET':
            if ($resource === 'show_status' && is_numeric($id)) {
                return $controller->showStatus($id);
            } elseif ($resource === 'show_status' && $id === 'list') {
                return $controller->listStatuses();
            } else {
                return [['error' => 'Not Found'], 404];
            }
        
            case 'POST':
            if ($resource === 'show_status') {
                return $controller->createStatus();
            } else {
                return [['error' => 'Not Found'], 404];
            }
        case 'PUT':
            if ($resource === 'show_status' && is_numeric($id)) {
                return $controller->updateStatus($id);
            } else {
                return [['error' => 'Not Found'], 404];
            }
        case 'DELETE':
            if ($resource === 'show_status' && is_numeric($id)) {
                $controller->deleteStatus($id);
            } else {
                return [['error' => 'Not Found'], 404];
            }
        default:
            return [['error' => 'Method Not Allowed'], 405];
    }
}