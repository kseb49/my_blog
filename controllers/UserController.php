<?php

namespace controllers;

use core\Controller;
use models\UserModel;

class UserController extends Controller{
    
    public function logIn(array $input) {
        $user = new UserModel();
        if($user->check($input)){
            $_SESSION['user'] = $user->user;
            $this->redirect("dashboard");
        }
        // header("Location: ".BASE."inscription");
        // die();
        
    }

    public function dashboard(){
        if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
            $this->twig->display('dashboard.html.twig');
            die();
        }
       $this->redirect();
    }

    public function logOut(){
        unset($_SESSION['user']);
        $this->redirect();
    }
}

