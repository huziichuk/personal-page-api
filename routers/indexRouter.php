<?php

global $router, $pdo;

$router->get("/", function() {
    echo json_encode(["message" => "Hello World!"]);
});