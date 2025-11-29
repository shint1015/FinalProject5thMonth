<?php

function json_response($data, int $status = 200)
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function get_json_input(): array
{
    $raw = file_get_contents('php://input');

    if (!$raw) {
        return [];
    }

    $data = json_decode($raw, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        json_response(['error' => 'Invalid JSON'], 400);
    }

    return $data;
}

function error_response(string $message, int $status = 400)
{
    json_response(['error' => $message], $status);
}

function success_response($data = [], int $status = 200)
{
    json_response(['data' => $data], $status);
}

function validate_required(array $data, array $required)
{
    foreach ($required as $field) {
        if (!isset($data[$field]) || $data[$field] === '') {
            error_response("Field '{$field}' is required", 422);
        }
    }
}