<?php
require_once __DIR__ . '/../config/env.php';
require_once __DIR__ . '/../config/database.php';

// ==== Custom Autoloader (for App\ namespace, no Composer used) ==== //
spl_autoload_register(function ($class) {
    // Target namespace prefix
    $prefix  = 'App\\';
    $baseDir = __DIR__ . '/src/';

    // Ignore classes that don't start with "App\"
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Get the relative class name
    $relativeClass = substr($class, $len);

    // Replace namespace separators with directory separators, append with .php
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

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