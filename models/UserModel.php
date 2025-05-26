<?php

class UserModel
{
    private pdo $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getByLogin($login):array
    {
        $sql = "SELECT * FROM users WHERE login = :login";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['login' => $login]);
        return $stmt->fetch();
    }
}