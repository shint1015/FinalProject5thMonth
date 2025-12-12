<?php
ini_set("display_errors", 1);
include __DIR__ ."/config/env.php";
include_once __DIR__ . '/src/Routes/ShowStatusRoute.php';
include_once __DIR__ . '/src/Routes/ShowStatusRoute.php';
include_once __DIR__ . '/src/Routes/ReservationRoute.php';
include_once __DIR__ . '/src/Routes/PaymentRoute.php';
include_once __DIR__ . '/src/Routes/SeatRoute.php';

$method = $_SERVER["REQUEST_METHOD"];
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
    } else {
        responseHandler(["error" => "Not Found"], 404);
    }
}