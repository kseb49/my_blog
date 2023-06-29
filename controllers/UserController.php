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
        $_SESSION['errors'] = ['login' => 'Identifiants incorrects'];
        $this->redirect('inscription');
    }
    
    public function logOut(){
        unset($_SESSION['user']);
        $this->redirect();
    }

    public function dashboard(){
        if($this->isUser()){
            $this->twig->display('dashboard.html.twig');
        }
       $this->redirect();
    }

    public function createPost(){
        if($this->isUser()){
            $this->twig->display('create-post.twig');
        }
    }

}

