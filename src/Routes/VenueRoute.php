<?php
require_once __DIR__ . '/../Middlewares/AuthMiddleware.php';

use App\Middleware\AuthMiddleware;

// Ensure all Venue endpoints require authentication

// Existing route handling continues below
// ...

include_once __DIR__ . '/../Controllers/VenueController.php';

function VenueRouter(string $pathInfo, string $method): array {
    
    $protectedMethods = ['POST', 'PUT', 'DELETE'];
    if (in_array($method, $protectedMethods, true)) {
        AuthMiddleware::requireAdmin();
    }
    $c = new VenueController();
    $parts = array_values(array_filter(explode('/', $pathInfo)));
    // paths: venue, venue/{id}
    if (($parts[0] ?? '') !== 'venue') {
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
