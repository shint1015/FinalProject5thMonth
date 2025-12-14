<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

include __DIR__ ."/config/env.php";
include_once __DIR__ . '/src/Routes/ShowStatusRoute.php';
require_once __DIR__ . '/src/Helpers/AppLogger.php';

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
    } else {
        responseHandler(["error" => "Not Found"], 404);
    }
}

// Handlers for POST requests
function handlePost($pathInfo) {
    if ($pathInfo === 'show_status' || $pathInfo === 'show_status/list') {
        ShowStatusRouter($pathInfo, "POST");
    } else {
        responseHandler(["error" => "Not Found"], 404);
    }
}

// Handlers for PUT requests
function handlePut($pathInfo) {
    if ($pathInfo === 'show_status' || $pathInfo === 'show_status/list') {
        ShowStatusRouter($pathInfo, "PUT");
    } else {
        responseHandler(["error" => "Not Found"], 404);
    }

}

// Handlers for DELETE requests
function handleDelete($pathInfo) {
    if ($pathInfo === 'show_status' || $pathInfo === 'show_status/list') {
        ShowStatusRouter($pathInfo, "DELETE");
    } else {
        responseHandler(["error" => "Not Found"], 404);
    }
}