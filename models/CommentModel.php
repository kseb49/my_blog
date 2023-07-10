<?php

namespace models;

use core\ValidateModel;

class CommentModel extends ValidateModel
{
    /**
     * A bunch of comments
     *
     * @var array
     */
    public array $comments;
    /**
     * A comment
     *
     * @var string
     */
    public string $comment;

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

    
    public function rules() :array{
        return [
            'comment' =>[self::REQUEST_REQUIRED,[self::REQUEST_MIN,3],[self::REQUEST_MAX,200]],
            'post_id' =>[self::REQUEST_REQUIRED,[self::REQUEST_MIN,1]]
        ];
    }

    public function createComment() :bool{
        $request = $this->connect()->prepare('INSERT into comments (comment,_date,status,posts_id,users_id) values(:comment,NOW(),0,:post_id,:user)');
        if($request->execute([
            "comment"=> $this->comment,
            "post_id"=> $this->post_id,
            "user"=> $_SESSION['user']['id']
        ])) {
            //recover the last comment Id for the post, from the user and only one in the event that there are similar comments.
            $request = $this->connect()->prepare('SELECT id from comments where comment = ? and posts_id = ? and users_id = ? and status = 0 ORDER BY _date DESC LIMIT 0,1');
            $request->execute([$this->comment,$this->post_id,$_SESSION['user']['id']]);
            if($response = $request->fetch()) {
               $this->id_comment = $response['id'];
               return true;
            }
        }
        return false;
    }

    /**
     * Get all the comments linked to a posts
     *
     * @param string $id post id
     * @return bool
     */
    public function fetch (string $id) :bool {
        $request = $this->connect()->prepare(
        'SELECT * FROM comments left join users on users.id = comments.users_id where comments.posts_id = ? order by comments._date desc');
        $request->execute([$id]);
        if($this->comments = $request->fetchAll()){
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
    public function single (string $id) :bool {
        $request = $this->connect()->prepare(
        'SELECT * FROM comments where id =?');
        $request->execute([$id]);
        if($response = $request->fetch()) {
            $this->comment = $response['comment'];
            return true;
        }
        return false;
    }

    public function pendingComments () :bool{
        $request = $this->connect()->query(
        'SELECT *,c.id as idcom FROM comments c join users u on u.id = c.users_id where c.status = 0  order by c._date desc');
        if($this->comments = $request->fetchAll()) {
            return true;
        }
        return false;
    }

    public function acceptComment(string $id) :bool {
        $request = $this->connect()->prepare('UPDATE comments set status = 1 where id = ?');
        if($request->execute([$id])){
            return true;
        }
        return false;
    }

    public function deleteComment(string $id){
        $request = $this->connect()->prepare('DELETE from comments where id = ?');
        if($request->execute([$id])){
            return true;
        }
        return false;
    }
    
}
