<?php
function ShowStatusRouter(string $pathInfo, string $method):array {
    include_once __DIR__ . '/../Controllers/ShowStatusController.php';
    include_once __DIR__ . '/../Helpers/jwt.php';
    include_once __DIR__ . '/../../config/env.php';
    $controller = new ShowStatusController();

    $pathParts = explode('/', $pathInfo);
    $resource = $pathParts[0] ?? '';
    $id = $pathParts[1] ?? '';

    switch ($method) {
        case 'GET':
            if ($resource === 'show_status' && is_numeric($id)) {
                return $controller->showStatus($id);
            } elseif ($resource === 'show_status' && $id === 'list') {
                return $controller->listStatuses();
            } else {
                return [['error' => 'Not Found'], 404];
            }
            // Protected routes: require valid Bearer JWT
            $protectedMethods = ['POST', 'PUT', 'DELETE'];
            if (in_array($method, $protectedMethods, true)) {
                $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
                if (!preg_match('/^Bearer\s+(\S+)/i', $authHeader, $m)) {
                    return [["error" => "Unauthorized"], 401];
                }
                $token = $m[1];
                $payload = jwt_decode($token, JWT_SECRET, 'HS256');
                if ($payload === null) {
                    return [["error" => "Unauthorized"], 401];
                }
                // Optionally use $payload['sub'] for auditing
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