<?php

namespace App\Middlewares;

use DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

/**
 * DAteTimeExpired
 * 
 * Classe responsável por altenticar o acesso a uma rota.
 */
class DateTimeExpired extends Middleware {
    
    /**
     * Método responsável por verificar se o token fornecido não expirou.
     *
     * @param  Request $request  PSR7 request
     * @param  RequestHandler $handler PSR15 response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, RequestHandler $handler)
    {
        $playroad = $request->getAttribute('jwt');

        $expired = $playroad["expiredAt"];

        $now = (new DateTime())->format('Y-m-d H:i:s');

        if ( strtotime($now) > strtotime($expired)) {

            $response = new Response();

            $response->getBody()->write((string) $response->getBody() . json_encode([
                'error' => "Acesso negado."
            ]));

            return $response->withStatus(401);
        }

        return parent::next($request, $handler);

    }
}