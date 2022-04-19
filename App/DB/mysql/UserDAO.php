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

    /**
     * getUSerByLogin
     * 
     * Recebe um login verifica no banco se tem o registro, caso não tenha retorna nulo e
     * se tiver retorna um objeto do tipo User.
     * @param string $login
     * @return ?User $user 
     */
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

    /**
     * getUSerById
     * 
     * Recebe um id verifica no banco se tem o registro, caso não tenha retorna nulo e
     * se tiver retorna um objeto do tipo User.
     * @param int $login
     * @return ?User $user 
     */
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

    /**
     * save
     * 
     * Recebe um objeto do tipo User e salva no banco.
     * @param string $login
     * @return User $user 
     */
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

    /**
     * update
     * 
     * Recebe um objeto do tipo User e atualiza os dados no banco.
     * @param string $login
     * @return User $user 
     */
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

    /**
     * save
     * 
     * Recebe um idUser e deleta o registro no banco.
     * @param string $login
     * @return User $user 
     */
    public function delete(int $idUser)
    {
        try {
            
            parent::query('CALL sp_users_delete(:idUser)',[
                ':idUser' => $idUser 
            ]);
        
        } catch (\Exception $e) {
            
            throw new DataBaseException($e->getMessage());

        }
    }
}