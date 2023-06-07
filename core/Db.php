<?php 
namespace core;

abstract class Db {

    protected $dsn;
    protected $user;
    protected $password;

    public function __construct()
    {
        if (file_exists(dirname(__DIR__)).'/env.json'){
            $datas = json_decode(file_get_contents(dirname(__DIR__).'/env.json'));
            $this->dsn = 'mysql:dbname='.$datas->db->dbname.';host='.$datas->db->host;
            $this->user = $datas->db->user;
            $this->password = $datas->db->password;

        }
    }
}