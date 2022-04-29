<?php

namespace App\Middlewares;

use App\DB\mysql\TokenDAO;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

/**
 * DAteTimeExpired
 * 
 * Classe responsável por altenticar o acesso a uma rota.
 */
class VerifyLogin extends Middleware{
    
    
    /**
     * Método responsável por verificar se o token fornecido está salvo no banco.
     *
     * @param  Request $request  PSR7 request
     * @param  RequestHandler $handler PSR15 response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, RequestHandler $handler)
    {
        $authorization = $request->getHeaders()["Authorization"][0];
                
        $arrayAuthorization = explode(" ", $authorization, 2);

        $token = $arrayAuthorization[1];

        $tokenDao = new TokenDAO();

        if (!$tokenDao->verifyToken($token)) {

            $response = new Response();

            $response->getBody()->write((string) $response->getBody() . json_encode([
                'error'      => true,
                'message'    => "Acesso negado.",
                'data'       => []
            ]));

            return $response->withStatus(401);
        }

        return parent::next($request, $handler);

    }
}