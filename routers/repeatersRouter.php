<?php

global $router, $pdo;

$router->get("/repeaters", function() use ($pdo) {
    $controller = new RepeatersController($pdo);
    $controller->index();
});

$router->get("/repeaters/{id}", function($id) use ($pdo) {
    $controller = new RepeatersController($pdo);
    $controller->show($id);
});

$router->post("/repeaters", function() use ($pdo) {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $controller = new RepeatersController($pdo);
    $controller->store($data);
});

$router->put("/repeaters", function($id) use ($pdo) {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $controller = new RepeatersController($pdo);
    $controller->update($id, $data);
});

$router->delete("/repeaters", function($id) use ($pdo) {
    $controller = new RepeatersController($pdo);
    $controller->delete($id);
});