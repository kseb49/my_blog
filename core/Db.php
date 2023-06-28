<?php 
namespace core;


use PDO;


class Db {

    protected $dsn;
    protected $user;
    protected $password;

    private static $instance;

    protected function __construct()
    {
        if (file_exists(PARAMS)){
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
    public static function requestDb(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * establish the connection
     *
     * @return PDO
     */
    public function connect():PDO{
           
        $db = new PDO($this->dsn,$this->user,$this->password,
        [
            PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
        ]);
            return $db;
    }


}