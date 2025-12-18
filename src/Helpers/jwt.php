<?php
function base64url_encode(string $data): string
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode(string $data): string|false
{
    $remainder = strlen($data) % 4;
    if ($remainder) {
        $data .= str_repeat('=', 4 - $remainder);
    }
    return base64_decode(strtr($data, '-_', '+/'));
}

function jwt_encode(array $payload, string $secret, string $alg = 'HS256'): string
{
    $header = ['typ' => 'JWT', 'alg' => $alg];

    $segments   = [];
    $segments[] = base64url_encode(json_encode($header, JSON_UNESCAPED_UNICODE));
    $segments[] = base64url_encode(json_encode($payload, JSON_UNESCAPED_UNICODE));

    $signingInput = implode('.', $segments);

    switch ($alg) {
        case 'HS256':
            $signature = hash_hmac('sha256', $signingInput, $secret, true);
            break;
        default:
            throw new RuntimeException('Unsupported JWT alg: ' . $alg);
    }

    $segments[] = base64url_encode($signature);

    return implode('.', $segments);
}

/**
 * @return array|null payload or null if invalid/expired
 */
function jwt_decode(string $jwt, string $secret, string $alg = 'HS256'): ?array
{
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) {
        return null;
    }

    [$encodedHeader, $encodedPayload, $encodedSignature] = $parts;

    $headerJson  = base64url_decode($encodedHeader);
    $payloadJson = base64url_decode($encodedPayload);
    $signature   = base64url_decode($encodedSignature);

    if ($headerJson === false || $payloadJson === false || $signature === false) {
        return null;
    }

    $header  = json_decode($headerJson, true);
    $payload = json_decode($payloadJson, true);

    if (!is_array($header) || !is_array($payload)) {
        return null;
    }

    if (($header['alg'] ?? '') !== $alg) {
        return null;
    }

    $signingInput = $encodedHeader . '.' . $encodedPayload;
    $expectedSig  = hash_hmac('sha256', $signingInput, $secret, true);

    if (!hash_equals($expectedSig, $signature)) {
        return null;
    }

    // Check exp if present
    if (isset($payload['exp']) && time() >= (int)$payload['exp']) {
        return null;
    }

    return $payload;
}

/**
 * Validate an Authorization header containing a Bearer JWT.
 * Returns the decoded payload if valid (signature verified and not expired), otherwise null.
 */
function jwt_check_bearer(string $authorizationHeader, string $secret, string $alg = 'HS256'): ?array
{
    $authorizationHeader = trim($authorizationHeader);
    if (!preg_match('/^Bearer\s+(.+)$/i', $authorizationHeader, $matches)) {
        return null;
    }
    $token = trim($matches[1] ?? '');
    if ($token === '') {
        return null;
    }
    return jwt_decode($token, $secret, $alg);
}