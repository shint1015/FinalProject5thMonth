<?php

include_once __DIR__ . '/../Controllers/CategoryController.php';
require_once __DIR__ . '/../Middlewares/AuthMiddleware.php';

use App\Middleware\AuthMiddleware;

function CategoryRouter(string $pathInfo, string $method): array {
    $c = new CategoryController();
    $protectedMethods = ['POST', 'PUT', 'DELETE'];
    if (in_array($method, $protectedMethods, true)) {
        AuthMiddleware::requireAdmin();
    }
    $parts = array_values(array_filter(explode('/', $pathInfo)));
    if (($parts[0] ?? '') !== 'category') {
        return [["error" => "Not Found"], 404];
    }
    $id = isset($parts[1]) ? (int)$parts[1] : null;

    if ($method === 'GET') {
        if ($id) return $c->show($id);
        return $c->list();
    }
    if ($method === 'POST' && !$id) {
        return $c->create();
    }
    if ($method === 'PUT' && $id) {
        return $c->update($id);
    }
    if ($method === 'DELETE' && $id) {
        return $c->delete($id);
    }
    return [["error" => "Not Found"], 404];
}
