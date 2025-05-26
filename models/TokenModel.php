<?php

class TokenModel
{
    private pdo $pdo;

    public function __construct(pdo $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get(string $token): array
    {
        $sql = "SELECT * FROM `tokens` WHERE `token` = :token";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create(array $data): void
    {
        $sql = "INSERT INTO tokens (`user_id`,`token`,`expires_in`) VALUES(:user_id,:token,:expires_in)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            "user_id" => $data['userId'],
            "token" => $data['token'],
            "expires_in" => $data['expiresIn']
        ]);
    }

    public function delete(string $token): void
    {
        $sql = "DELETE FROM `tokens` WHERE token=:token";
        $stmt = $this->pdo->prepare($sql);
        $stmt-> execute([
            "token" => $token
        ]);
    }
}