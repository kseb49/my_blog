<?php

namespace models;

use core\Model;

class CommentModel extends Model
{
    /**
     * Get all the comments bounded to a posts
     *
     * @param string $id
     * @return array
     */
    public function fetch (string $id):array{
        $request = $this->connect()->prepare(
        'SELECT * FROM comments left join users on users.id = comments.users_id where comments.posts_id = ? and comments.status = 1 order by comments._date desc');
        $request->execute([$id]);
        $resp = $request->fetchAll();
        return $resp;
    }
}
