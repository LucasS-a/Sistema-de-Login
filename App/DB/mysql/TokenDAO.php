<?php

namespace App\DB\mysql;

use App\Model\Token;
use Exception;

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
    
}