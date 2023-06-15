<?php

namespace models;

use PDO;
use PDOStatement;
use Exception;

class BlogModel 
{
    private PDO $db;

    public function __construct(PDO $db){
        $this->db = $db;
    }

    public function index():array{

        if($request = $this->db->query('SELECT * FROM posts')->fetchAll()){
            return $request;
        }
        throw new Exception("Aucun article n'a été trouvé :( ");
    }
    
}
