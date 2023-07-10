<?php

namespace controllers;

use utils\Flash;
use core\Controller;
use models\PostModel;
use models\UserModel;
use models\CommentModel;

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
            $this->redirect('referer');
        }
        $_SESSION['flash']  = ['danger' => 'pseudo ou mot de passe incorrects'];
        $this->redirect('referer');
    }
    

    
    public function logOut(){
        unset($_SESSION['user']);
        $this->redirect();
    }

    public function dashboard() {
        $user = new PostModel();
        if($this->isUser()) {
            if($this->isUser(ADMIN)) {
                if($user->allPosts()) {
                    $all = $user->post;
                    $comment = new CommentModel();
                    if($comment->pendingComments()){
                        return $this->twig->display('dashboard.html.twig',["pend_comments"=>$comment->comments,"posts"=>$all]);
                    }
                    return $this->twig->display('dashboard.html.twig',["posts"=>$all]);
                }
                return $this->twig->display('dashboard.html.twig');
            }
            if(!$user->fetch() && empty($user->post)) {
                return $this->twig->display('dashboard.html.twig');
            }
            $post = $user->post;
            return $this->twig->display('dashboard.html.twig',['posts'=>$post]);
        }
        Flash::flash('danger','Connectez vous');
        $this->redirect('inscription');
    }
}




