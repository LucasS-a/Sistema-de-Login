<?php

namespace App\Models\Classes;

use App\Models\Model;
use App\DB\mysql\TokenDAO;
use App\Exception\TokenException;
use Firebase\JWT\JWT;
use DateTime;

class Token extends Model{

     /**
     * createToken
     * 
     * Método que cria um token.
     * @param User $user
     * @result Token
     */
    public function CreateToken(User $user):Token
    {
        try {
            $hoje = new DateTime();

            $expiredAt = $hoje->modify('+1 day')->format('Y-m-d H:i:s');

            $payLoad = [
                'sub'       => $user->getIdUser(),
                'name'      => $user->getName(),
                'email'     => $user->getEmail(),
                'expiredAt' => $expiredAt
            ];

            $token = JWT::encode($payLoad, getenv('JWT_SECRET'));

            $payLoad = [
                'email' => $user->getEmail(),
                // Garante que o refresh_token não gere sempre o mesmo token.
                'ramdom' => uniqid()
            ];

        $tokenRefresh = JWT::encode($payLoad, getenv('JWT_SECRET'));

            $this->setValues([
                'idUser'        => $user->getIdUser(),
                'token'         => $token,
                'refreshToken'  => $tokenRefresh,
                'expiredAt'     => $expiredAt,
                'active'        => true
            ]);

            $tokenDAO = new TokenDAO;

            $tokenDAO->save($this);

            return $this;
        } catch ( TokenException $e ) {
            throw new TokenException($e->getMessage());
        }
    }

    /**
     * get($name, $arg)
     * 
     * Método responsável por buscar os valores do objeto.
     */
    public function get($name)
    {
        try {
            
            return parent::get($name);

        } catch (\Exception $e) {
            throw new TokenException($e->getMessage());
        }
    }

}