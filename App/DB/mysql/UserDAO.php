<?php

namespace App\DB\mysql;

use App\Model\User;
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

    // testando a conexão
    public function save()
    {
    }
}