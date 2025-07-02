<?php

global $router, $pdo;

$router->get("/repeatersFields", function() use ($pdo) {
    $controller = new RepeaterFieldsController($pdo);
    $controller->index();
});

$router->get("/repeatersFields/{id}", function($id) use ($pdo) {
    $controller = new RepeaterFieldsController($pdo);
    $controller->show($id);
});

$router->post("/repeatersFields", function() use ($pdo) {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $controller = new RepeaterFieldsController($pdo);
    $controller->store($data);
});

$router->put("/repeatersFields/{id}", function($id) use ($pdo) {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $controller = new RepeaterFieldsController($pdo);
    $controller->update($id, $data);
});

$router->delete("/repeatersFields/{id}", function($id) use ($pdo) {
    $controller = new RepeaterFieldsController($pdo);
    $controller->remove($id);
});