<?php

namespace core;

use PDO;
use core\Db;
use Exception;

abstract class Model
{

    protected Db $db;
    protected PDO $connection;

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
}
