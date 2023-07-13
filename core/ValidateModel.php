<?php

namespace core;

use Exception;


abstract class ValidateModel extends Model
{

    protected const REQUEST_REQUIRED = 'required';
    protected const REQUEST_MIN = "min";
    protected const REQUEST_MAX = "max";
    protected const REQUEST_EMAIL = 'is_email';
    protected const REQUEST_UNIQUE = "unique";
    protected const REQUEST_MATCHING = 'match';


    /**
     * The rules to validate
     *
     * @return array
     */
    abstract protected function rules() :array;
    
    /**
    * Load the datas incoming from a form
    *
    * @param array $datas
    * @return void
    */
    public function loadDatas(array $datas)
    {
        foreach ($datas as $key => $value) {
            if (str_contains($key,"#") === false) { //input name starting with # are excluded .Those could be hidden input (just needed to security concern) per exemple 
                if (property_exists($this,$key) === false) {
                    throw new Exception("Le champ {$key} est invalide");
                }
            }
            $this->$key = htmlspecialchars($value);
        }
        return $this;

    }


    /**
     * Validate the loaded datas
     *
     * @return boolean
     */
    public function validate():bool
    {
        foreach ($this->rules() as $input_name => $data) {
            $value = $this->$input_name;
            foreach ($data as $rules) {
                $rule = $rules;
                if (is_array($rule) === true) { 
                    $rule = $rules[0];
                }
                if ($rule === self::REQUEST_REQUIRED && !$value) {
                    throw new Exception("Le champ {$input_name} est obligatoire");
                }
                if ($rule === self::REQUEST_MIN && strlen($value) < $rules[1]) {
                    throw new Exception("Le champ {$input_name} ne peut être inferieur à {$rules[1]} caracteres");
                }
                if ($rule === self::REQUEST_MAX && strlen($value) > $rules[1]) {
                    throw new Exception("Le champ {$input_name} ne peut être superieur à {$rules[1]} caracteres");
                }
                if ($rule === self::REQUEST_EMAIL && filter_var($value,FILTER_VALIDATE_EMAIL) === false) {
                    throw new Exception("Le champ {$input_name} doit être de type email");
                }
                if ($rule === self::REQUEST_MATCHING && $value !== $this->{$rules[1]}) {
                    throw new Exception("Les champs {$input_name} et {$rules[1]} doivent être identiques");
                }
                if ($rule === self::REQUEST_UNIQUE) {
                    $request = $this->connect()->prepare('SELECT * from users where '.$input_name.' = ?');
                    $request->execute([$value]);
                    if ($request->fetch()) {
                        throw new Exception("Il existe deja un utilisateur avec ces identifiants : ".$input_name);
                    }
                    $request->closeCursor();
                }
            } //end foreach.
        }
        return true;

    }

}
