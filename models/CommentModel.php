<?php

namespace models;

use core\Model;

class CommentModel extends Model
{
    /**
     * Get all the comments linked to a posts
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
    public function comment (string $comment) {
        $request = $this->connect()->prepare(
        'SELECT * FROM comments left join users on users.id = comments.users_id where comments.posts_id = ? and comments.status = 1 order by comments._date desc');
        $request->execute([$id]);
        $resp = $request->fetchAll();
        return $resp;
    }
    // public function has_categories (string $id):array{
    //     $request = $this->connect()->prepare(
    //     'SELECT c.id FROM categories c join posts_has_categories p on p.categories_id = c.id where p.posts_id = ?');
    //     $request->execute([$id]);
    //     $resp = $request->fetchAll();
    //     return $resp;
    // }
}
