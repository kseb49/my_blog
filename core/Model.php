<?php

namespace core;

use PDO;
use core\Db;
use Exception;

abstract class Model
{

    protected Db $db;
    protected PDO $connection;

    public const TIMEZONE = "Europe/Paris";


    public function __construct()
    {
        $this->db = Db::requestDb();
        date_default_timezone_set(self::TIMEZONE);
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
