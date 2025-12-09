<?php
ini_set("display_errors", 1);
include __DIR__ ."/config/env.php";
include_once __DIR__ . '/src/Routes/ShowStatusRoute.php';
include_once __DIR__ . '/src/Routes/AuthRoute.php';

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
        $response = ShowStatusRouter($pathInfo, "POST");
        responseHandler(...$response);
        return;
    }else if (str_contains($pathInfo, 'auth')) {
        $response = AuthRouter($pathInfo, 'POST');
        responseHandler(...$response);
        return;
    }
}

// Handlers for POST requests
function handlePost($pathInfo) {
    if (str_contains($pathInfo, 'show_status')) {
        $response = ShowStatusRouter($pathInfo, "POST");
        responseHandler(...$response);
        return;
    }else if (str_contains($pathInfo, 'auth')) {
        $response = AuthRouter($pathInfo, 'POST');
        responseHandler(...$response);
        return;
    }
    responseHandler(["error" => "Not Found"], 404);
}

// Handlers for PUT requests
function handlePut($pathInfo) {
    if (str_contains($pathInfo, 'show_status')) {
        $response = ShowStatusRouter($pathInfo, "POST");
        responseHandler(...$response);
        return;
    }
    if (str_contains($pathInfo, 'auth')) {
        $response = AuthRouter($pathInfo, 'POST');
        responseHandler(...$response);
        return;
    }
    responseHandler(["error" => "Not Found"], 404);

}

// Handlers for DELETE requests
function handleDelete($pathInfo) {
    if (str_contains($pathInfo, 'show_status')) {
        $response = ShowStatusRouter($pathInfo, "POST");
        responseHandler(...$response);
        return;
    }
    if (str_contains($pathInfo, 'auth')) {
        $response = AuthRouter($pathInfo, 'POST');
        responseHandler(...$response);
        return;
    }
    responseHandler(["error" => "Not Found"], 404);
}