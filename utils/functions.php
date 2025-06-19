<?php

use Firebase\JWT\JWT;

function generateAccessToken($userId): string
{
    $issuedAt = time();
    $expirationTime = $issuedAt + ACCESS_TOKEN_EXPIRATION;

    $payload = [
        'userId' => $userId,
        'iat' => $issuedAt,
        'exp' => $expirationTime,
    ];

    return JWT::encode($payload, ACCESS_TOKEN_SECRET, 'HS256');
}

function decodeAccessToken(string $token): ?stdClass
{
    try {
        return JWT::decode($token, new Firebase\JWT\Key(ACCESS_TOKEN_SECRET, 'HS256'));
    } catch (Exception) {
        return null;
    }
}

function generateRefreshToken($userId): string
{
    $issuedAt = time();
    $expirationTime = $issuedAt + REFRESH_TOKEN_EXPIRATION;

    $payload = [
        'userId' => $userId,
        'iat' => $issuedAt,
        'exp' => $expirationTime,
    ];

    return JWT::encode($payload, REFRESH_TOKEN_SECRET, 'HS256');
}

function clearTokens(): void
{
    setcookie("refresh_token", "", time() - REFRESH_TOKEN_EXPIRATION, "/", false, false);
    setcookie("access_token", "", time() - ACCESS_TOKEN_EXPIRATION, "/", false, false);
}

function decodeRefreshToken(string $token): ?stdClass
{
    try {
        return JWT::decode($token, new Firebase\JWT\Key(REFRESH_TOKEN_SECRET, 'HS256'));
    } catch (Exception) {
        return null;
    }
}

function validateRequiredFields(array $data, array $requiredFields): void
{
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            http_response_code(400);
            echo json_encode(array("message" => "$field is required"));
            exit;
        }
    }
}

