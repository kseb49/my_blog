<?php

namespace models;

use PDO;

class CommentModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {   
        $this->db = $db;
        
    }

    public function fetch (string $id):array{
        $request = $this->db->prepare(
        'SELECT * FROM comments left join users on users.id = comments.users_id where comments.posts_id = ? and comments.status = 1');
        $request->execute([$id]);
        $resp = $request->fetchAll();
        return $resp;
    }
}
