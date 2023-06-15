<?php 
namespace core;

use Exception;
use PDO;
use PDOException;

class Db {

    protected $dsn;
    protected $user;
    protected $password;

    private static $instance;

    protected function __construct()
    {
        if (file_exists(dirname(__DIR__)).'/env.dev.json'){
            $datas = json_decode(file_get_contents(dirname(__DIR__).'/env.dev.json'));
            $this->dsn = 'mysql:dbname='.$datas->db->dbname.';host='.$datas->db->host;
            $this->user = $datas->db->user;
            $this->password = $datas->db->password;
        }
    }

    public static function requestDb(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function connect():PDO{
           
            $db = new PDO($this->dsn,$this->user,$this->password,
            [
                PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
            ]);
             return $db;
    }


}