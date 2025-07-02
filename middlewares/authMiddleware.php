<?php

global $pdo;

function authMiddleware(): void
{
    global $pdo;
    $refreshToken = $_COOKIE['refresh_token'] ?? null;
    if (!$refreshToken) {
        http_response_code(401);
        echo json_encode(['error' => 'No refresh token']);
        exit;
    }

    $decodedRefreshToken = decodeRefreshToken($refreshToken);

    if (!$decodedRefreshToken) {
        http_response_code(401);
        echo json_encode(['error' => 'No refresh token']);
        exit;
    }

    $accessToken = $_COOKIE['access_token'] ?? null;

    if ($accessToken) {
        $decodedAccessToken = decodeAccessToken($accessToken);
        if ($decodedAccessToken) {
            return;
        }
    }

    $tokenModel = new TokenModel($pdo);
    $token = $tokenModel->get($refreshToken);

    if (!$token) {
        clearTokens();
        http_response_code(401);
        echo json_encode(['error' => 'Invalid refresh token']);
        exit;
    }

    setcookie("access_token", generateAccessToken($token["user_id"]), time()+ACCESS_TOKEN_EXPIRATION, "/", false, false);
}