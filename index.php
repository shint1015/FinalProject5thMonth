<?php
declare(strict_types=1);
// index.php

// ---- Load environment config & database connection ---- //
require_once __DIR__ . '/config/env.php';
require_once __DIR__ . '/config/database.php';

// Load common helper functions (json_response, etc.)
require_once __DIR__ . '/src/Helpers/response.php';

// ---- Custom Autoloader (for App\ namespace, no Composer used) ---- //
spl_autoload_register(function ($class) {
    // Target namespace prefix
    $prefix  = 'App\\';
    $baseDir = __DIR__ . '/src/';

    // Ignore classes that don't start with "App\"
    if (strpos($class, $prefix) !== 0) {
        return;
    }

    // Remove prefix and convert namespace to file path
    $relative = substr($class, strlen($prefix)); // e.g. Controllers\HomeController
    $file = $baseDir . str_replace('\\', '/', $relative) . '.php';

    // Load file if exists
    if (file_exists($file)) {
        require_once $file;
    }
});


// ---- CORS Headers ---- //
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

// Preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


// ---- Load Routes ---- //
$routes = require __DIR__ . '/src/Routes/routes.php';


// ---- Parse Request Method & URI ---- //
$method = $_SERVER['REQUEST_METHOD'];
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove /api prefix if present
$uri = preg_replace('#^/api#', '', $uri);


// ---- Route Matching Function ---- //
function matchRoute(string $method, string $uri, array $routes): ?array
{
    foreach ($routes as $route) {
        [$rMethod, $rPath, $handler] = $route;

        if ($method !== $rMethod) {
            continue;
        }

        // Convert route parameters: /users/{id} → /users/([^/]+)
        $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $rPath);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches); // Remove full match
            return [$handler, $matches];
        }
    }
    return null;
}


// ---- Main Request Handling ---- //
try {
    // Try to match route
    $result = matchRoute($method, $uri, $routes);

    if (!$result) {
        json_response(['error' => 'Not Found'], 404);
        exit;
    }

    [$handler, $params] = $result;

    // "HomeController@index" → split into controller & method
    [$controllerName, $methodName] = explode('@', $handler);

    // Fully-qualified class name (autoload will load it)
    $controllerClass = 'App\\Controllers\\' . $controllerName;

    // Check class existence
    if (!class_exists($controllerClass)) {
        json_response(['error' => 'Controller class not found'], 500);
        exit;
    }

    // Create controller instance
    $controller = new $controllerClass();

    // Check method existence
    if (!method_exists($controller, $methodName)) {
        json_response(['error' => 'Method not found'], 500);
        exit;
    }

    // Execute controller method with parameters
    call_user_func_array([$controller, $methodName], $params);

} catch (Throwable $e) {
    // Return error details (shown for learning purposes)
    json_response([
        'error'   => 'Internal Server Error',
        'message' => $e->getMessage()
    ], 500);
}