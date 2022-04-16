<?php 

namespace App\Models\Classes;

use App\Models\Model;
use App\Exception\UserException;

class User extends Model{

    public function setValues(array $values)
    {
        foreach ($values as $key => $value) {
            switch ($key) {
                case 'name':
                    if ( strlen($value) < 3 || strlen($value) > 30 ) {
                        throw new UserException('Digite um nome com mais de 3 letras e menos que 30.');
                    }
            
                    if( !preg_match('|^[\pL\s]+$|u', $value) ) {
                        throw new UserException('O nome só pode conter letras.');
                    }

                    $this->{"set".$key}($value);

                    break;
                case 'lastName':
                    if ( strlen($value) < 3 || strlen($value) > 30 ) {
                        throw new UserException('Digite um sobrenome com mais de 3 letras e menos que 30.');
                    }
                    if( !preg_match('|^[\pL\s]+$|u', $value) ) {
                        throw new UserException('O sobrenome só pode conter letras.');
                    }

                    $this->{"set".$key}($value);

                    break;
                case 'email':
                    if( !filter_var($value, FILTER_VALIDATE_EMAIL )) {

                        throw new UserException('Insira um email válido.');
                    
                    }

                    $this->{"set".$key}($value);

                    break;
                case 'login':
                    if ( strlen($value) < 5 || strlen($value) > 30 ) {
                        throw new UserException('Digite um login com mais de 5 letras e menos que 30.');
                    }
                    if ( !ctype_alnum($value)) {
                        throw new UserException('O login deve ser alfanumérico.');
                    }

                    $this->{"set".$key}($value);

                    break;                
                default:
                    
                    $this->{"set".$key}($value);

                    break;
            }
        }
    }

}