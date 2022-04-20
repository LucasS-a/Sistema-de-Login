<?php

namespace routes;

use App\Controller\AuthController;
use App\Controller\UserController;
use App\Middlewares\DateTimeExpired;
use App\Middlewares\VerifyLogin;
use Slim\Factory\AppFactory;
use function src\conf\JwtAuth;

$app = AppFactory::create();

$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->post('/login', AuthController::class . ':login');

$app->post('/register', UserController::class . ':register');

$app->group('/user', function($app){

    $app->put('/update', UserController::class . ':update');

    $app->delete('/delete', UserController::class . ':delete');

    $app->get('/logout', AuthController::class . ':logout');

})
->add(new DateTimeExpired)
->add(new VerifyLogin)
->add(JwtAuth());

$app->get('/refresh_token', AuthController::class . ':refreshToken')
->add(JwtAuth());

$app->run();