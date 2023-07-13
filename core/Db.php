<?php 
namespace core;


use PDO;


class Db {

    protected $dsn;

    protected $user;

    protected $password;

    private static $instance;

    /**
     * Get the params from env file
     */
    protected function __construct()
    {
        if (file_exists(PARAMS) === true) {
            $datas = json_decode(file_get_contents(PARAMS));
            $this->dsn = 'mysql:dbname='.$datas->db->dbname.';host='.$datas->db->host;
            $this->user = $datas->db->user;
            $this->password = $datas->db->password;
        }

    }


    /**
     * create a instance of Db - SINGLETON
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
