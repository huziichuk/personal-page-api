<?php

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");
    http_response_code(200);
    exit();
}

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

require 'vendor/autoload.php';

require_once "utils/config.php";
require_once "utils/connect.php";
require_once "utils/functions.php";

require_once "models/TokenModel.php";
require_once "models/UserModel.php";
require_once "models/PagesModel.php";
require_once "models/FieldsModel.php";
require_once "models/RepeatersModel.php";
require_once "models/RepeaterFieldsModel.php";

require_once "Router.php";

require_once "controllers/AuthController.php";
require_once "controllers/PagesController.php";
require_once "controllers/FieldsController.php";
require_once "controllers/RepeatersController.php";
require_once "controllers/RepeaterFieldsController.php";

header('Content-Type: application/json');

$router = new Router();

require_once "middlewares/authMiddleware.php";

require_once "routers/indexRouter.php";
require_once "routers/authRouter.php";
require_once "routers/pagesRouter.php";
require_once "routers/fieldsRouter.php";
require_once "routers/repeatersRouter.php";
require_once "routers/repeaterFieldsRouter.php";

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$router->resolve($method, $uri);