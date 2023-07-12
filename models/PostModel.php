<?php

namespace models;


use core\ValidateModel;

class PostModel extends ValidateModel {


    public string $title;
    public string $chapo;
    public string $content;
    public ?string $author = null;

    public array $post;

    public function rules() :array{
        return[
            'title'=>[self::REQUEST_REQUIRED,[self::REQUEST_MIN,10],[self::REQUEST_MAX,100]],
            'chapo'=>[self::REQUEST_REQUIRED,[self::REQUEST_MIN,15],[self::REQUEST_MAX,200]],
            'content'=>[self::REQUEST_REQUIRED,[self::REQUEST_MIN,150],[self::REQUEST_MAX,5000]],
            'author'=>[],
          ];
    }

    public function createPost(string $img) {
        $request = $this->connect()->prepare('INSERT into posts (title,chapo,content,created_at,users_id,img) VALUES (:title,:chapo,:content,NOW(),:users_id,:img)');
        if($request->execute([
            ":title" => $this->title,
            ":chapo" => $this->chapo,
            ":content" => $this->content,
            ":users_id" => $this->author ?? $_SESSION['user']['id'],
            ":img" => $img
            ])) {
                
                return true;
            }
            return false;
        }
    
    /**
     * Fetch the post to edit
     *
     * @param string $id
     * @return boolean
     */
   public function postToEdit(string $id) :bool {
    $request = $this->connect()->prepare('SELECT * FROM posts where id = ?');
    if($request->execute([$id])){
        if($this->post = $request->fetchAll()){
            return true;
        }
             return false;
        }
        return false;
    }

    /**
     * Get all the posts owned by an user
     *
     * @return bool
     */
    public function fetch() :bool {
        $request = $this->connect()->query('SELECT * from posts where users_id = '.$_SESSION['user']['id'].'');
        if($post = $request->fetchAll()){
            $this->post = $post;
            return true;
        }
        return false;
    }


    public function allPosts() :bool {
    $request = $this->connect()->query('SELECT * FROM posts');
    if($this->post = $request->fetchAll()){
        return true;
    }
        return false;
    }

    /**
     * Edit a post
     *
     * @param array $datas
     * @param string|null $img_name (is null if the image of the post is not changed)
     * @return boolean
     */
    public function postEdit(array $datas,?string $img_name = null) :bool {
        $request = $this->connect()->prepare('UPDATE posts set title = :title, chapo = :chapo,content = :content ,users_id = :users_id,last_updated = NOW(),img = :img where id = :id');
        if($request->execute([
            ":title" => $datas['title'],
            ":chapo" => $datas['chapo'],
            ":content" => $datas['content'],
            ":users_id" =>$this->author ?? $_SESSION['user']['id'],
            ":img" => $img_name ?? $datas['#img'],
            ":id" => $datas['#id']
            ])) {
                
                return true;
            }
            return false;
        }

   /**
    * Delete a single post
    *
    * @param string $id
    * @return boolean
    */
   public function delete(string $id) :bool {
    $request = $this->connect()->prepare('DELETE FROM posts where id = ?');
    if($request->execute([$id])){
        $request->closeCursor();
        $request = $this->connect()->prepare('DELETE FROM posts_has_categories where posts_id = ?');
        if($request->execute([$id])){
            return true;
        }
    }
    return false;
   }
}

    

