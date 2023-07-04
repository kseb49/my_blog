<?php

namespace controllers;

use core\Controller;
use models\UserModel;

class UserController extends Controller{
    

    public function logIn(array $input) {
        $user = new UserModel();
        if($user->check($input)){
            if ($user->user['confirmation_date'] === null){
                $_SESSION['flash']  = ['danger' => "le compte n'est pas confirmé"];
                return $this->twig->display('registration.twig');
            }
            if(password_verify($input['password'],$user->user['password'])) {
                $_SESSION['user'] = $user->user;
                $_SESSION['user']['token'] = hash('md5',uniqid(true));
                $this->redirect('dashboard');
            }
            $_SESSION['flash']  = ['danger' => 'pseudo ou mot de passe incorrects'];
            return $this->twig->display('registration.twig');
        }
        $_SESSION['flash']  = ['danger' => 'pseudo ou mot de passe incorrects'];
        return $this->twig->display('registration.twig');
    }
    

    
    public function logOut(){
        unset($_SESSION['user']);
        $this->redirect();
    }

    public function dashboard(){
        if($this->isUser()){
            $user = new UserModel();
            if($user->fetch()){
                $post = $user->user_post;
                 return $this->twig->display('dashboard.html.twig',['posts'=>$post]);
            }
        }
       $this->redirect();
    }

}

