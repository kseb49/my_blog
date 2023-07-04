<?php

namespace models;


use Exception;
use core\Model;


class BlogModel extends Model
{
    /**
     * Get all the posts and their associated authors
     *
     * @return array
     */
    public function index():array{

        if($response = $this->connect()->query('SELECT *, p.id FROM posts p inner join users u on u.id = p.users_id')->fetchAll()){
           return $response;
        }
        throw new Exception("Aucun article n'a été trouvé :( ");
    }
    /**
     * get a single post and its associated author
     *
     * @param string $id
     * @return array
     */
    public function single(string $id):array{

        $request = $this->connect()->prepare('SELECT * FROM posts p join users u on u.id = p.users_id where p.id = ?');
        $request->execute([$id]);
        if($resp = $request->fetchAll()){
            return $resp;
        }
        throw new Exception("Cet article n'existe pas :(");
    }

    /**
     * Get the last three posts
     *
     * @return array
     */
    public function home():array{
        if($request = $this->connect()->query('SELECT * FROM posts order by created_at desc limit 3')->fetchAll()){

            return $request;
        }
        throw new Exception('Impossible de récupérer les articles');
    }
}
         

    
