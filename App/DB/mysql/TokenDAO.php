<?php

namespace App\DB\mysql;

use App\Exception\DataBaseException;
use App\Models\Classes\Token;

/**
 * TokenDAO
 * 
 * Classe que intermedia o a relação do token com o Banco.
 */
class TokenDAO extends Conect {

    /**
     * Faz a conexão com o banco
     */
    public function __construct()
    {
        parent::__construct();        
    }

    public function save(Token $token) {

        try {
            parent::query('INSERT INTO tb_tokens (
                idUser,
                token,
                refreshToken,
                expiredAt,
                active)
            Values (
                :idUser,
                :token,
                :refreshToken,
                :expiredAt,
                :active)',[
                    ':idUser'        => $token->getIdUser(),
                    ':token'         => $token->getToken(),
                    ':refreshToken' => $token->getRefreshToken(),
                    ':expiredAt'    => $token->getExpiredAt(),
                    ':active'        => $token->getActive()
                ]);
        } catch (\Exception $e) {

            throw new DataBaseException($e->getMessage());
        
        }
    }
    
    
    /**
     * delete
     * 
     * Método que exclui os tokens relacionado a esse id user.
     * @param int $iduser
     * @return void
     */
    public function delete(int $idUser):void
    {
        try {
            parent::query("DELETE FROM tb_tokens WHERE idUser = :idUser",[
                ":idUser" => $idUser
            ]);
        } catch (\Exception $e) {
            throw new DataBaseException($e->getMessage());
        }

    }

    /**
     * verifyToken
     * 
     * Verifica se tem o token cadastrado no banco.
     * @param string $token
     * @retun bool  
     */
    public function verifyToken(string $token):bool
    {

        try {
            $result = parent::select('SELECT id FROM tb_tokens WHERE token = :token',[
                ':token' => $token
            ]);
    
            if(count($result) > 0)
            {
                return true;
            }else {
                return false;
            }

        } catch (\Exception $e) {
            throw new DataBaseException($e->getMessage());
        }

    }
}