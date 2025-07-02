<?php


global $pdo;
require_once 'utils/config.php';
require_once 'utils/connect.php';

$stmt = $pdo->prepare("INSERT INTO users (login, password) values (:login, :password)");

$stmt->execute([
    'login' => ADMIN_LOGIN, 'password' => password_hash(ADMIN_PASSWORD, PASSWORD_DEFAULT)
]);