<?php
ini_set("display_errors", 1);
// include __DIR__ ."/config/env.php";
// include_once __DIR__ . '/src/Routes/ShowStatusRoute.php';
// include_once __DIR__ . '/src/Routes/ShowsRoute.php';
require_once __DIR__ . "/src/Routes/ShowsRoute.php";


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

    } else if (str_contains($pathInfo, 'shows')) {
        $response = ShowsRouter($pathInfo, "GET");
        responseHandler(...$response);

    } else {
        responseHandler(["error" => "Not Found"], 404);
    }
}

// Handlers for POST requests
function handlePost($pathInfo) {
    if ($pathInfo === 'show_status' || $pathInfo === 'show_status/list') {
        ShowStatusRouter($pathInfo, "POST");

    } else if ($pathInfo === 'shows') {
        $response = ShowsRouter($pathInfo, "POST");
        responseHandler(...$response);

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
?>