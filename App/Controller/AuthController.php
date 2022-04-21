<?php

namespace App\Controller;

use App\DB\mysql\TokenDAO;
use App\Models\Classes\Token;
use App\DB\mysql\UserDAO;
use App\Exception\TokenException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * AuthController
 * 
 * Classe responsável por controlar o fluxo de dados referente as autentições.
 */
class AuthController {

    /**
     * login
     * 
     * Método responsável por receber login e senha, buscar no banco se tem cadastro no banco.
     * @param Request $request
     * @param Response $response
     */
    public function login(Request $request, Response $response)
    {
        $contents = json_decode($request->getBody()->getContents(), true);

        $login = $contents['login'];

        $password = $contents['password'];

        try {

            $userDao = new UserDAO;

            $user = $userDao->getUserByLogin($login);

            if (is_null($user))
            {
                $response->getBody()->write(json_encode([
                    'error' => 'Login ou senha inválidos'
                ]));
    
                return $response->withStatus(404);
            
            } else if ($user->getPassword() !== $password) {

                $response->getBody()->write(json_encode([
                    'error' => 'Login ou senha inválidos'
                ]));
    
                return $response->withStatus(404);
            }else {

                $token = new Token;

                $token = $token->CreateToken($user);

                $response->getBody()->write(json_encode([
                    'token' => $token->getToken(),
                    'refreshToken' => $token->getRefreshToken()
                ]));
    
                return $response->withStatus(200);

            }            
        }catch (TokenException $e) {

            $response->getBody()->write(json_encode([
                'Token error' => $e->getMessage()
            ]));

            return $response->withStatus(500);

        }catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));

            return $response->withStatus(500);
        }
    }

    public function logout(Request $request, Response $response):Response
    {
        try {

            $idUser = $request->getAttribute('jwt')['sub'];

            $userDao = new UserDAO;

            $user = $userDao->getUserById($idUser);

            $tokenDao = new TokenDAO;

            $tokenDao->logout($user);

            $response->getBody()->write(json_encode(['succesfully']));

            return $response->withStatus(200);

        } catch (TokenException $e) {

            $response->getBody()->write(json_encode([
                'Token error' => $e->getMessage()
            ]));

            return $response->withStatus(500);

        } catch (\Exception $e) {

            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));

            return $response->withStatus(500);
        }
    }

    /**
     * refreshToken
     * 
     * Recebe um refresh token, verifica se tem o mesmo no banco e se está ativo. Se tiver desativa e 
     * cria um novo token e retorna.
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response $response
     * 
     */
    public function refreshToken(Request $request, Response $response):Response
    {
        try {
            $authorization = $request->getHeaders()["Authorization"][0];

            $arrayAuthorization = explode(" ", $authorization, 2);

            $refreshToken = $arrayAuthorization[1];

            $tokenDAO = new TokenDAO;

            if(!$tokenDAO->verifyRefreshToken($refreshToken))
            {
                return $response->withStatus(401);
            }

            $email = $request->getAttribute('jwt')['email'];

            $userDAO = new UserDAO;

            $user = $userDAO->getUserByEmail($email);

            $tokenModel = new Token;

            $tokenModel->CreateToken($user);

            $response->getBody()->write(json_encode([
                "token" => $tokenModel->getToken(),
                "refresh_token" => $tokenModel->getRefreshToken()
            ]));

            return $response->withStatus(200);

        } catch (TokenException $e) {

            $response->getBody()->write(json_encode([
                'Token error' => $e->getMessage()
            ]));

            return $response->withStatus(500);

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));

            return $response->withStatus(500);
        }
    }
}