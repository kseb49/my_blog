<?php 

namespace models;

use core\Model;

class UserModel extends Model{

    public array $user;
    public array $user_post;

    public function check(array $datas) : bool {
        $request = $this->connect()->prepare('SELECT * from users where pseudo = ?');
        $request->execute([$datas['pseudo']]);
        if($user = $request->fetch()) {
            $this->user = $user;
            return true;
        }
        return false;
    }

    public function fetch(){
        $request = $this->connect()->query('SELECT * from posts where users_id = '.$_SESSION['user']['id'].'');
        if($post = $request->fetchAll()){
            $this->user_post = $post;
            return true;
        }
        return false;
    }
}
