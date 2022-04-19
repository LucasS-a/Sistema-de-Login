<?php

namespace App\Models\Classes;

use App\Models\Model;
use App\DB\mysql\TokenDAO;
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
    }

}