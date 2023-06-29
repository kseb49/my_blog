<?php 

namespace models;

use core\Model;

class UserModel extends Model{

    public array $user;

    public function check(array $datas) : bool {
        $request = $this->connect()->prepare('SELECT * from users where pseudo = ?');
        $request->execute([$datas['pseudo']]);
        if($user = $request->fetch()) {
            $this->user = $user;
            return true;
        }
        return false;
    }
}
