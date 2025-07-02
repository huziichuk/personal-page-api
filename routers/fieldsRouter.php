<?php

global $router, $pdo;

$router->get("/fields/{id}", function($id) use ($pdo) {
    $controller = new FieldsController($pdo);
    $controller->show($id);
});

$router->post("/fields", function() use ($pdo) {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $controller = new FieldsController($pdo);
    $controller->store($data);
});

$router->get("/fields", function() use ($pdo) {
    $controller = new FieldsController($pdo);
    $controller->index();
});

$router->post("/fields/{id}", function($id) use ($pdo) {
    $controller = new FieldsController($pdo);
});