<?php

class AuthController
{
    private pdo $pdo;

    public function __construct(pdo $pdo)
    {
        $this->pdo = $pdo;
    }

    public function login(array $data): void
    {
        validateRequiredFields($data, ["login", "password"]);

        $userModel = new UserModel($this->pdo);

        $user = $userModel->getByLogin($data['login']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            http_response_code(401);
            echo json_encode(["message" => "Invalid login or password"]);
            exit;
        }

        $refreshToken = generateRefreshToken($user['id']);
        $expiresIn = time() + REFRESH_TOKEN_EXPIRATION;
        $expiresInSqlFormat = date('Y-m-d H:i:s', $expiresIn);

        $tokenModel = new TokenModel($this->pdo);
        $tokenModel->create(["userId" => $user['id'], "token" => $refreshToken, "expiresIn" => $expiresInSqlFormat]);
        setcookie("refresh_token", $refreshToken, $data["rememberMe"] ?? false ? $expiresIn : 0, "/", false, false);
        setcookie("access_token", generateAccessToken($user['id']), time()+ACCESS_TOKEN_EXPIRATION, "/", false, false);

        http_response_code(200);
        echo json_encode(["message" => "Logged in successfully"]);

    }
}