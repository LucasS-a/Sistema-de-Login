<?php

namespace routes;

use App\DB\mysql\UserDAO;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response, $args) {
    $userDAO = new UserDAO();
    $userDAO->save();
    $body = $response->getBody();
    $body->write("message: Salvo com sucesso");
    return $response->withBody($body);
});

$app->run();

?>