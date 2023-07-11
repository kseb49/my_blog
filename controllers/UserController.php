<?php

namespace controllers;

use core\Auth;
use utils\Flash;
use core\Controller;
use models\PostModel;
use models\UserModel;
use models\CommentModel;

class UserController extends Controller{
    

    public function logIn(array $input) {
        if(!Auth::isConnect()) {
            $user = new UserModel();
            if($user->check($input)){
                if ($user->user['confirmation_date'] === null){
                    Flash::flash('danger',"le compte n'est pas confirmé");
                    return $this->twig->display('registration.twig');
                }
                if(password_verify($input['password'],$user->user['password'])) {
                    $_SESSION['user'] = $user->user;
                    $_SESSION['user']['token'] = hash('md5',uniqid(true));
                    $this->redirect('dashboard');
                }
                Flash::flash('danger','pseudo ou mot de passe incorrects');
                $this->redirect(REF);
            }
            Flash::flash('danger','pseudo ou mot de passe incorrects');
            $this->redirect(REF);
        }
        $this->redirect('dashboard');
    }
    

    
    public function logOut(){
        unset($_SESSION['user']);
        $this->redirect();
    }

    public function dashboard() {
        $user = new PostModel();
             if(!Auth::getRole(ADMIN)) {
                 if(!$user->fetch() && empty($user->post)) {
                     return $this->twig->display('dashboard.html.twig');
                 }
                 $post = $user->post;
                 return $this->twig->display('dashboard.html.twig',['posts'=>$post]);
             }
                    $user->allPosts();
                    $all = $user->post;
                    $comment = new CommentModel();
                    $comment->all();
                    $comment->pendingComments();
                        return $this->twig->display('dashboard.html.twig',["pend_comments"=>$comment->pending_comments,"posts"=>$all,"comments"=>$comment->comments]);
                    }
                }
    




