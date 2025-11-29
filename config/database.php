<?php
// config/database.php
// Simple PDO helper function

use PDO;
use PDOException;

if (!defined('DB_HOST')) {
    // Make sure env.php was loaded first
    throw new RuntimeException('env.php must be loaded before database.php');
}

/**
 * Get a shared PDO instance.
 * Usage: $pdo = db();
 */
function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        DB_HOST,
        DB_PORT,
        DB_DATABASE,
        DB_CHARSET
    );

    try {
        $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        // For learning you can expose the message, in production you should hide it
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'error'   => 'Database connection failed',
            'message' => $e->getMessage(),
        ]);
        exit;
    }

    return $pdo;
}