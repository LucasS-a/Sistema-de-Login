<?php

namespace App\Controller;

use App\Models\Classes\Token;
use App\DB\mysql\UserDAO;
use App\Exception\UserException;
use App\Exception\DataBaseException;
use App\Models\Classes\User;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * UserController
 * 
 * Classe responsável por controlar o fluxo de dados referente ao user.
 */
class UserController {

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
    
                return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
            
            } else if ($user->getPassword() !== $password) {

                $response->getBody()->write(json_encode([
                    'error' => 'Login ou senha inválidos'
                ]));
    
                return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
            }else {

                $token = new Token;

                $token = $token->CreateToken($user);

                $response->getBody()->write(json_encode([
                    'token' => $token->getToken(),
                    'refreshToken' => $token->getRefreshToken()
                ]));
    
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

            }            
        }catch (DataBaseException $e) {

            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);

        }catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    /**
     * register
     * 
     * Método  responsável por receber os dados e cadastra no banco de dados.
     * @param $request
     * @param $response
     */
    public function register(Request $request, Response $response)
    {
        $contents = json_decode($request->getBody()->getContents(), true);
        
        $user = new User;
        
        try {

            $user->setValues($contents);
            
            $userDao = new UserDAO;

            $user = $userDao->save($user);

            $response->getBody()->write(json_encode([
                'successfully'
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

        } catch (UserException $e) {
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }catch (\Exception $e)
        {
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }

        return $response;
    }

    /**
     * update 
     * 
     * Método responsável por rerceber os dados e atualizar no banco.
     * @param $request
     * @param $response
     */
    public function update(Request $request, Response $response)
    {
        $contents = json_decode($request->getBody()->getContents(), true);

        try {
            $jwt = $request->getAttribute('jwt');

            $idUser = $jwt['sub'];
            
            $userDao = new UserDAO;

            $user = $userDao->getUserById($idUser);

            $user->setValues($contents);

            $user = $userDao->update($user);

            $response->getBody()->write(json_encode([
                'successfully'
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

        } catch (UserException $e) {
            
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    /**
     * delete
     * 
     * Deleta o registro de um usuário autenticado.
     * @param Request $request
     * @param Response $resonse
     */
    public function delete(Request $request, Response $response)
    {
        try {
            $jwt = $request->getAttribute('jwt');

            $idUser = $jwt['sub'];
            
            $userDao = new UserDAO;

            $userDao->delete($idUser);
            
            $response->getBody()->write(json_encode([
                'successfully'
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

        } catch (UserException $e) {
            
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
    
}