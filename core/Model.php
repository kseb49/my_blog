<?php

namespace core;

use PDO;
use core\Db;
use Exception;

abstract class Model
{

    protected Db $db;
    protected PDO $connection;

    protected const REQUEST_REQUIRED = 'required';
    protected const REQUEST_MIN = "min";
    protected const REQUEST_MAX = "max";
    protected const REQUEST_EMAIL ='is_email';
    protected const REQUEST_UNIQUE = "unique";
    protected const REQUEST_MATCHING = 'match';


    public function __construct()
    {
        $this->db = Db::requestDb();
    }

    /**
     * retrieve the data base connection
     *
     * @return PDO
     */
    protected function connect():PDO {
        return $this->connection = $this->db->connect();
    }

    // abstract public function rules();

    /**
     * Load the datas posted in the Registration model
     *
     * @param array $datas
     * @return void
     */
    public function loadDatas(array $datas){
        foreach($datas as $key => $value){
            if (!property_exists($this,$key)){
                throw new Exception("Le champ {$key} est invalide");
            }
           $this->$key = $value;
        }
        return $this;
    }

    public function validate():bool{
        foreach ($this->rules() as $input_name => $data) {
            $value = $this->$input_name;
            foreach ($data as $rules) {
                $rule = $rules;
                if(is_array($rule)) { 
                    $rule = $rules[0];
                }
               if($rule === self::REQUEST_REQUIRED && !$value) {
                    throw new Exception("Le champ {$input_name} est obligatoire");
               }
               if($rule === self::REQUEST_EMAIL && !filter_var($value,FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Le champ {$input_name} doit être de type email");
               }
               if($rule === self::REQUEST_MIN && strlen($value)<$rules[1]) {
                    throw new Exception("Le champ {$input_name} ne peut être inferieur à {$rules[1]} caracteres");
               }
               if($rule === self::REQUEST_MAX && strlen($value)>$rules[1]) {
                    throw new Exception("Le champ {$input_name} ne peut être superieur à {$rules[1]} caracteres");
               }
               if($rule === self::REQUEST_MATCHING && $value !== $this->{$rules[1]}) {
                    throw new Exception("Les champs {$input_name} et {$rules[1]} doivent être identiques");
               }

            }

            }
            return true;
        }

        
    
}
