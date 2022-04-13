<?php

namespace routes;

use App\Controller\UserController;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get('/', UserController::class . ':teste');

$app->run();