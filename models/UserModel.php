<?php 

namespace models;

use core\Model;

class UserModel extends Model{

    public array $user;

    public function check(array $datas) : bool {
        $request = $this->connect()->prepare('SELECT * from users where pseudo = ?');
        $request->execute([$datas['pseudo']]);
        if($user = $request->fetch()) {
            if ($user['confirmation_date'] === null){
                $_SESSION['error']  = ['connexion' => "le compte n'est pas confirmé"];
                return false;
            }
            if(password_verify($datas['password'],$user['password'])) {
                $this->user = $user;
                return true;
            }
            $_SESSION['error']  = ['connexion' => 'pseudo ou mot de passe incorrects'];
            return false;
        }
        $_SESSION['error']  = ['connexion' => 'pseudo ou mot de passe incorrects'];
        return false;
    }
}
