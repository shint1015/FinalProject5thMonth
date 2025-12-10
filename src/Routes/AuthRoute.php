<?php

function AuthRouter(string $pathInfo, string $method):array {
    include_once __DIR__ . '/../Controllers/AuthController.php';
    $authController = new AuthController();
    // Expected path: auth/login
    $parts = array_values(array_filter(explode('/', $pathInfo)));
    $resource = $parts[0] ?? '';
    $action = $parts[1] ?? '';

    if ($resource === 'auth' && $action === 'login' && $method === 'POST') {
        return $authController->login();
    }
    if ($resource === 'auth' && $action === 'signup' && $method === 'POST') {
        return $authController->signup();
    }
    if ($resource === 'auth' && $action === 'logout' && $method === 'POST') {
        return $authController->logout();
    }

    return [["error" => "Not Found"], 404];
}