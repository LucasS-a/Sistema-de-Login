<?php

namespace App\DB\mysql;

use App\Exception\DataBaseException;
use App\Models\Classes\User;
use Exception;

/**
 * TokenDAO
 * 
 * Classe que intermedia o a relação dos objetos User com o Banco.
 */
class UserDAO extends Conect {

    /**
     * Faz a conexão com o banco.
     */
    public function __construct()
    {
        parent::__construct();        
    }

    public function getUserByLogin($login):?User
    {
        try {
            $result = parent::select('SELECT * FROM tb_users WHERE login = :login',[
                ':login' => $login
            ]);

            if ( count($result) === 0 )
            {
                return null;
            }
    
            $user = new User;
    
            $user->setValues($result[0]);
    
            return $user;
        } catch (\Exception $e) {

            throw new DataBaseException($e->getMessage());
        
        }
    }

    public function getUserById($idUser):?User
    {
        try {
            $result = parent::select('SELECT * FROM tb_users WHERE idUser = :idUser',[
                ':idUser' => $idUser
            ]);

            if ( count($result) === 0 )
            {
                return null;
            }
    
            $user = new User;
    
            $user->setValues($result[0]);
    
            return $user;
        } catch (\Exception $e) {

            throw new DataBaseException($e->getMessage());
        
        }
    }

    public function save(User $user):User
    {
        try {

            $result = parent::select('CALL sp_users_save(
                :name,
                :lastName,
                :login,
                :password,
                :email,
                :gender
            )', [
                ':name'     => $user->getName(),
                ':lastName' => $user->getLastName(),
                ':login'    => $user->getLogin(),
                ':password' => $user->getPassword(),
                ':email'    => $user->getEmail(),
                ':gender'   => $user->getGender()
            ]);

            $user = new User;
            
            $user->setValues($result[0]);

            return $user;

        } catch (\Exception $e) {
            
            throw new DataBaseException($e->getMessage());
        
        }
    }

    public function update(User $user):User
    {
        try {
            
            $result = parent::select('CALL sp_usersupdate_save(
                :idUser,
                :name,
                :lastName,
                :login,
                :password,
                :email,
                :gender
            )', [
                ':idUser'   => $user->getIdUser(),
                ':name'     => $user->getName(),
                ':lastName' => $user->getLastName(),
                ':login'    => $user->getLogin(),
                ':password' => $user->getPassword(),
                ':email'    => $user->getEmail(),
                ':gender'   => $user->getGender()
            ]);

            $user = new User;
            
            $user->setValues($result[0]);

            return $user;

        } catch (\Exception $e) {
            
            throw new DataBaseException($e->getMessage());
        
        }
    }

    public function delete(int $idUser)
    {
        try {

            parent::query('DELETE FROM tb_users WHERE idUser = :idUser',[
                ':idUser' => $idUser 
            ]);
        
        } catch (\Exception $e) {
            
            throw new DataBaseException($e->getMessage());

        }
    }
}