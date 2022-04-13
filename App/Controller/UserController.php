<?php

namespace App\Controller;

use App\Exception\ModelException;
use App\Models\Classes\User;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class UserController {

    public function teste(Request $request, Response $response)
    {
        $user = new User();

        try {

            $user->setName("lucas");

            echo $user->getNamee();
        
        } catch (ModelException $e) {
        
            echo $e->getMessage();
        
        } catch (\Exception $e) {
        
            echo $e->getMessage();
        
        }

        return $response;
    }
}