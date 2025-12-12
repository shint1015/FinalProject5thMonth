<?php
// Start session early for session-based auth
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ini_set("display_errors", 1);
error_reporting(E_ALL);

include __DIR__ ."/config/env.php";
include_once __DIR__ . '/src/Routes/ShowStatusRoute.php';
include_once __DIR__ . '/src/Routes/ShowStatusRoute.php';
include_once __DIR__ . '/src/Routes/ReservationRoute.php';
include_once __DIR__ . '/src/Routes/PaymentRoute.php';
include_once __DIR__ . '/src/Routes/SeatRoute.php';
require_once __DIR__ . '/src/Helpers/AppLogger.php';
include_once __DIR__ . '/src/Routes/AuthRoute.php';
include_once __DIR__ . '/src/Routes/VenueRoute.php';
include_once __DIR__ . '/src/Routes/CategoryRoute.php';

// appLog is provided by Helpers\AppLogger

function jsonError($message, $status = 500, $extra = []) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode(array_merge(['error' => $message], $extra), JSON_UNESCAPED_UNICODE);
}

try {
    $method = $_SERVER["REQUEST_METHOD"] ?? 'GET';
    $pathInfo = isset($_SERVER["PATH_INFO"]) ? ltrim($_SERVER["PATH_INFO"], '/') : '';
    switch ($method) {
        case 'GET':
            handleGet($pathInfo);
            break;
        case 'POST':
            handlePost($pathInfo);
            break;
        case 'PUT':
            handlePut($pathInfo);
            break;
        case 'DELETE':
            handleDelete($pathInfo);
            break;
        default:
            responseHandler(["error" => "Method Not Allowed"], 405);
            break;
        // fall through to handlers (omitted sections retained in included routes)
    }
} catch (Throwable $e) {
    appLog('CRITICAL', $e->getMessage(), [
        'endpoint' => $_SERVER['REQUEST_URI'] ?? '',
        'method' => $_SERVER['REQUEST_METHOD'] ?? '',
        'trace' => $e->getTraceAsString(),
    ]);
    jsonError('Internal server error', 500);
}

function responseHandler($data, $code) { 
    http_response_code($code);
    echo json_encode($data);
}


// Handlers for GET requests
function handleGet($pathInfo) {
    if (str_contains($pathInfo, 'show_status')) {
        $response = ShowStatusRouter($pathInfo, "GET");
        responseHandler(...$response);
    } else if (str_contains($pathInfo, 'reservation')) {
        $response = ReservationRouter($pathInfo, "GET");
        responseHandler(...$response);
    } else if (str_contains($pathInfo, 'payment')) {
        $response = PaymentRouter($pathInfo, "GET");
        responseHandler(...$response);
    } else if (str_contains($pathInfo, 'seat')) {
        $response = SeatRouter($pathInfo, "GET");
        responseHandler(...$response);
    }else if (str_contains($pathInfo, 'auth')) {
        $response = AuthRouter($pathInfo, 'GET');
        responseHandler(...$response);
    } else if ($pathInfo === 'venue' || preg_match('#^venue/\d+$#', $pathInfo)) {
        $response = VenueRouter($pathInfo, 'GET');
        responseHandler(...$response);
    } else if ($pathInfo === 'category' || preg_match('#^category/\d+$#', $pathInfo)) {
        $response = CategoryRouter($pathInfo, 'GET');
        responseHandler(...$response);
    } else {
        responseHandler(["error" => "Not Found"], 404);
    }
}

// Handlers for POST requests
function handlePost($pathInfo) {
    if (str_contains($pathInfo, 'show_status')) {
        $response = ShowStatusRouter($pathInfo, "POST");
        responseHandler(...$response);
    } else if (str_contains($pathInfo, 'reservation')) {
        $response = ReservationRouter($pathInfo, "POST");
        responseHandler(...$response);
    } else if (str_contains($pathInfo, 'payment')) {
        $response = PaymentRouter($pathInfo, "POST");
        responseHandler(...$response);
    } else if (str_contains($pathInfo, 'seat')) {
        $response = SeatRouter($pathInfo, "POST");
        responseHandler(...$response);
    }else if (str_contains($pathInfo, 'auth')) {
        $response = AuthRouter($pathInfo, 'POST');
        responseHandler(...$response);
    } else if ($pathInfo === 'venue') {
        $response = VenueRouter($pathInfo, 'POST');
        responseHandler(...$response);
    } else if ($pathInfo === 'category') {
        $response = CategoryRouter($pathInfo, 'POST');
        responseHandler(...$response);
    } else {
        responseHandler(["error" => "Not Found"], 404);
    }

}

// Handlers for PUT requests
function handlePut($pathInfo) {
    if (str_contains($pathInfo, 'show_status')) {
        $response = ShowStatusRouter($pathInfo, "PUT");
        responseHandler(...$response);
    } else if (str_contains($pathInfo, 'reservation')) {
        $response = ReservationRouter($pathInfo, "PUT");
        responseHandler(...$response);
    } else if (str_contains($pathInfo, 'payment')) {
        $response = PaymentRouter($pathInfo, "PUT");
        responseHandler(...$response);
    } else if (str_contains($pathInfo, 'seat')) {
        $response = SeatRouter($pathInfo, "PUT");
        responseHandler(...$response);
    } else if (str_contains($pathInfo, 'auth')) {
        $response = AuthRouter($pathInfo, 'PUT');
        responseHandler(...$response);
    } else if (preg_match('#^venue/\d+$#', $pathInfo)) {
        $response = VenueRouter($pathInfo, 'PUT');
        responseHandler(...$response);
    } else if (preg_match('#^category/\d+$#', $pathInfo)) {
        $response = CategoryRouter($pathInfo, 'PUT');
        responseHandler(...$response);
    } else {
        responseHandler(["error" => "Not Found"], 404);
    }

}

// Handlers for DELETE requests
function handleDelete($pathInfo) {
    if (str_contains($pathInfo, 'show_status')) {
        $response = ShowStatusRouter($pathInfo, "DELETE");
        responseHandler(...$response);
    } else if (str_contains($pathInfo, 'reservation')) {
        $response = ReservationRouter($pathInfo, "DELETE");
        responseHandler(...$response);
    } else if (str_contains($pathInfo, 'payment')) {
        $response = PaymentRouter($pathInfo, "DELETE");
        responseHandler(...$response);
    } else if (str_contains($pathInfo, 'seat')) {
        $response = SeatRouter($pathInfo, "DELETE");
        responseHandler(...$response);
    }else if (str_contains($pathInfo, 'auth')) {
        $response = AuthRouter($pathInfo, 'DELETE');
        responseHandler(...$response);
    } else if (preg_match('#^venue/\d+$#', $pathInfo)) {
        $response = VenueRouter($pathInfo, 'DELETE');
        responseHandler(...$response);
    } else if (preg_match('#^category/\d+$#', $pathInfo)) {
        $response = CategoryRouter($pathInfo, 'DELETE');
        responseHandler(...$response);
    } else {
        responseHandler(["error" => "Not Found"], 404);
    }
}