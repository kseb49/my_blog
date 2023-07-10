<?php 

namespace models;

use core\Model;

class UserModel extends Model{

    public array $user;
    public array $user_post;

    /**
     * Check if a user exists
     *
     * @param array $datas
     * @return boolean
     */
    public function check(array $datas) : bool {
        $request = $this->connect()->prepare('SELECT * from users where pseudo = ?');
        $request->execute([$datas['pseudo']]);
        if($user = $request->fetch()) {
            $this->user = $user;
            return true;
        }
        return false;
    }

    public function user(string $id) :bool {
        $request = $this->connect()->prepare('SELECT * from users where id = ?');
        $request->execute([$id]);
        if($this->user = $request->fetch()){
            return true;
        }
        return false;
    }

    public function users() :bool {
        $request = $this->connect()->query('SELECT * from users');
        if($this->user = $request->fetchAll()){
            return true;
        }
        return false;
    }
}
