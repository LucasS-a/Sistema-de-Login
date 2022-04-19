<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

/**
 * Middleware
 * 
 * Classe abstrata que servirá como base para as outras.
 */
abstract class Middleware{
    
    /**
     * next
     * 
     * Responsável por indicar o proximo passo.
     */
    protected function next(Request $request, RequestHandler $handler)
    {
        $response = $handler->handle($request);

        $existingContent = (string) $response->getBody();

        $response = new Response();

        $response->getBody()->write($existingContent);

        return $response;
    }
    
}