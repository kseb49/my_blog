<?php

namespace core;

use PDO;
use core\DbConfig;

abstract class Model
{

    /**
     * Instance of DbConfig
     *
     * @var DbConfig
     */
    protected DbConfig $d_b;

    /**
     * Connection to the db
     *
     * @var PDO
     */
    protected PDO $connection;

    public const TIMEZONE = "Europe/Paris";


    public function __construct()
    {
        $this->d_b = DbConfig::requestDb();
        date_default_timezone_set(self::TIMEZONE);

    }


    /**
     * retrieve the data base connection
     *
     * @return PDO
     */
    protected function connect() :PDO
    {
        return $this->connection = $this->d_b->connect();

    }


}
