<?php

namespace App\Controller;

use App\DB\mysql\UserDAO;
use App\Exception\UserException;
use App\Exception\DataBaseException;
use App\Models\Classes\User;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class UserController {

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

                $response->getBody()->write(json_encode([
                    'ok'
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

    public function update(Request $request, Response $response)
    {
        $contents = json_decode($request->getBody()->getContents(), true);

        try {
            // futuramento receberá do token.
            $idUser = 1;
            
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

    public function delete(Request $request, Response $response)
    {
        try {
            // futuramento receberá do token.
            $idUser = 3;
            
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