<?php

namespace core;

use PDO;


class DbConfig 
{

    /**
     * Data source name
     *
     * @var [type]
     */
    protected $dsn;

    /**
     * Username
     *
     * @var [type]
     */
    protected $user;

    /**
     * Db password
     *
     * @var [type]
     */
    protected $password;

    /**
     * Instance of Db
     *
     * @var [type]
     */
    private static ?DbConfig $instance = null;


    /**
     * Get the params from env file
     */
    protected function __construct()
    {
        $init = new Init();
        $init = $init->init()['db'];
        $this->dsn = $init['prefix'].':dbname='.$init['dbname'].';host='.$init['host'];
        $this->user = $init['user'];
        $this->password = $init['password'];
    }


    /**
     * create a instance of DbConfig - SINGLETON
     *
     * @return void
     */
    public static function requestDb()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;

    }


    /**
     * establish the connection
     *
     * @return PDO
     */
    public function connect():PDO
    {
        $d_b = new PDO($this->dsn, $this->user, $this->password,
        [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
        );
            return $d_b;

    }


}
