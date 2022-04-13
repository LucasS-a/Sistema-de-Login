<?php

namespace App\Models;

use App\Exception\ModelException;

/**
 * Model
 * 
 * Classe que servirá de modelo para as demais, tornando o sets e gets dinâmicos.
 */
abstract class Model {
    
    /**
     * @var array $values
     * 
     * Salva em um array os parâmetros da classe.
     */
    protected $values = array();

    /**
     * __call($name, $arguments)
     * 
     * Método mágico que pega as chamadas dos métodos gets e sets,
     * caso não siga o padrão extoura um erro.
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, $arguments)
    {
        $prefix = substr($name, 0, 3);

        $name = substr($name, 3, strlen($name) - 3);
        
        if ( $prefix === 'set') {
            
            $this->set(strtolower($name), $arguments[0]);

        }else if ($prefix === 'get') {
            
            return $this->get(strtolower($name));
        
        } else {
            throw new ModelException("Método não reconhecido.", 500);
        }
    }

    /**
     * set($name, $arg)
     * 
     * Método responsável por setar os valores do objeto.
     */
    protected function set($name, $arg)
    {
        $this->values[$name] = $arg;
    }

    /**
     * get($name, $arg)
     * 
     * Método responsável por buscar os valores do objeto.
     */
    protected function get($name)
    {
        if (isset($this->values[$name])) {
            
            return $this->values[$name];
        
        }else {
            throw new \Exception('Atributo não definido');
        }
    }
}