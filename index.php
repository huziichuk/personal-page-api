<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

require 'vendor/autoload.php';

require_once "./utils/config.php";
require_once "./utils/connect.php";
require_once "./utils/functions.php";

require_once "models/TokenModel.php";
require_once "models/UserModel.php";

require_once "Router.php";

require_once "controllers/AuthController.php";

header('Content-Type: application/json');

$router = new Router();

require "./routers/indexRouter.php";
require "./routers/authRouter.php";

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$router->resolve($method, $uri);