<?php

namespace models;

use core\ValidateModel;

class CommentModel extends ValidateModel
{

    /**
     * All the accepted comments
     *
     * @var array
     */
    public array $comments;

    /**
     * The pending comments
     *
     * @var array
     */
    public array $pending_comments;

    /**
     * A comment content
     *
     * @var string
     */
    public string $comment;

    /**
     * One comment
     *
     * @var array
     */
    public array $single_comment;

    /**
     * The comment Id
     *
     * @var string
     */
    public string $id_comment;

    /**
     * Id of the post commented
     *
     * @var string
     */
    public string $post_id;


    public function rules() :array
    {
        return [
            'comment' => [self::REQUEST_REQUIRED,[self::REQUEST_MIN,3],[self::REQUEST_MAX,200]],
            'post_id' => [self::REQUEST_REQUIRED,[self::REQUEST_MIN,1]]
        ];

    }


    public function createComment() :bool
    {
        $request = $this->connect()->prepare('INSERT into comments (comment,_date,status,posts_id,users_id) values(:comment,NOW(),0,:post_id,:user)');
        if ($request->execute([
            "comment" => $this->comment,
            "post_id" => $this->post_id,
            "user" => $_SESSION['user']['id']
        ])) {
            // Recover the last comment Id for the post, from the user and only one in the event that there are similar comments.
            $request = $this->connect()->prepare('SELECT id from comments where comment = ? and posts_id = ? and users_id = ? and status = 0 ORDER BY _date DESC LIMIT 0,1');
            $request->execute([$this->comment,$this->post_id,$_SESSION['user']['id']]);
            if ($response = $request->fetch()) {
               $this->id_comment = $response['id'];
               return true;
            }
        }
        return false;
    }


    public function editComment(string $com_id)
    {
        $request = $this->connect()->prepare("UPDATE comments set comment = :comment, _date = NOW(), status = 0 WHERE id = :id");
        if ($request->execute(["comment" => $this->comment,"id"=>$com_id])) {
            return true;
        }
        return false;
    }


    /**
     * Get all the comments linked to a posts
     *
     * @param string $id post id
     * @return bool
     */
    public function fetch(string $id) :bool
    {
        $request = $this->connect()->prepare(
        'SELECT * ,c.id as cid FROM comments c left join users on users.id = c.users_id where c.posts_id = ? order by c._date desc');
        $request->execute([$id]);
        if ($this->comments = $request->fetchAll()) {
            return true;
        }
        return false;
    }


    /**
     * get the content of a single comment
     *
     * @param string $id
     * @return boolean
     */
    public function single(string $id) :bool
    {
        $request = $this->connect()->prepare(
        'SELECT * FROM comments where id =?');
        $request->execute([$id]);
        if ($response = $request->fetch()) {
            $this->comment = $response['comment'];
            return true;
        }
        return false;
    }


    /**
     * Get a single comment
     *
     * @param string $id
     * @return void
     */
    public function oneCommment(string $id)
    {
        $request = $this->connect()->prepare('SELECT * FROM comments where id = ?');
        $request->execute([$id]);
        if ($response = $request->fetchAll()) {
            $this->single_comment = $response;
            return true;
        }
        return false;
    }


    /**
     * Get all the validated comments
     *
     * @return boolean
     */
    public function all() :bool
     {
        $request = $this->connect()->query(
        'SELECT * FROM comments where status = 1 order by _date desc');
        if ($this->comments = $request->fetchAll()) {
            return true;
        }
        return false;
    }


    public function pendingComments() :bool
    {
        $request = $this->connect()->query(
        'SELECT *,c.id as idcom FROM comments c join users u on u.id = c.users_id where c.status = 0  order by c._date desc');
        if ($this->pending_comments = $request->fetchAll()) {
            return true;
        }
        return false;
    }


    public function acceptComment(string $id) :bool
    {
        $request = $this->connect()->prepare('UPDATE comments set status = 1 where id = ?');
        if ($request->execute([$id])) {
            return true;
        }
        return false;
    }


    public function deleteComment(string $com_id)
    {
        $request = $this->connect()->prepare('DELETE from comments where id = ?');
        if ($request->execute([$com_id])) {
            return true;
        }
        return false;

    }

}
