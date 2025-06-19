<?php

global $router, $pdo;

$router->post('/login', function () use ($pdo) {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $controller = new AuthController($pdo);
    $controller->login($data);
});

$router->post('/logout', function () use ($pdo) {
    $controller = new AuthController($pdo);
    $controller->logout();
});

$router->get('/isAuth', function () use ($pdo) {
    $controller = new AuthController($pdo);
    $controller->isAuth();
});