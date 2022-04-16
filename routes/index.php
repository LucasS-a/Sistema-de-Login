<?php

namespace routes;

use App\Controller\UserController;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->post('/login', UserController::class . ':login');

$app->post('/register', UserController::class . ':register');

$app->put('/update', UserController::class . ':update');

$app->delete('/delete', UserController::class . ':delete');

$app->run();