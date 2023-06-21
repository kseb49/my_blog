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
    /**
     * Get all the posts and their authors
     *
     * @return array
     */
    public function index():array{

        if($request = $this->db->query('SELECT *, p.id FROM posts p inner join users u on u.id = p.users_id')->fetchAll()){
           return $request;
        }
        throw new Exception("Aucun article n'a été trouvé :( ");
    }
    /**
     * Undocumented function
     *
     * @param string $id
     * @return array
     */
    public function single(string $id):array{

        $request = $this->db->prepare('SELECT * FROM posts p where p.id = ?');
        $request->execute([$id]);
        if($resp = $request->fetchAll()){
            return $resp;
        }
        throw new Exception("Cet article n'existe pas :(");
    }

    public function home():array{
        if($request = $this->db->query('SELECT * FROM posts order by created_at desc limit 3')->fetchAll()){

            return $request;
        }
        throw new Exception('Impossible de récupérer les articles');
    }
}
         

    
