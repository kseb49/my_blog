<?php

namespace core;

use core\Db;
use PDO;

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
}
