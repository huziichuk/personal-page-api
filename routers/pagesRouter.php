<?php

global $router, $pdo;

$router->get("/pages", function() use ($pdo) {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $controller = new PagesController($pdo);
    $controller->index($data);
});

$router->post("/pages", function() use ($pdo)  {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $controller = new PagesController($pdo);
    $controller->store($data);
});

$router->get("/pages/{slug}", function($slug) use ($pdo)  {
    $controller = new PagesController($pdo);
    $controller->show($slug);
});