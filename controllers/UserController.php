<?php

namespace controllers;

use core\Auth;
use utils\Flash;
use core\Controller;
use Exception;
use models\PostModel;
use models\UserModel;
use models\CommentModel;

class UserController extends Controller
{

    public function logIn(array $input) {
        try {
            if (Auth::isConnect() === false) {
                $user = new UserModel();
                if ($user->check($input) === true) {
                    if ($user->user['confirmation_date'] === null) {
                        throw new Exception("le compte n'est pas confirmé");
                    }
                    if (password_verify($input['password'], $user->user['password'])) {
                        Auth::createUser($user->user); // Connect the user.
                        $this->redirect('dashboard');
                    }
                    throw new Exception("pseudo ou mot de passe incorrects");
                }
                throw new Exception("pseudo ou mot de passe incorrects");
            }
            $this->redirect('dashboard');
        } catch (Exception $e) {
            Flash::flash('danger',$e->getMessage());
            $this->redirect(REF);
        }
    }

    public function logOut()
    {
        Auth::destroy();
        $this->redirect();

    }

    public function dashboard() {
        $user = new PostModel();
        if (Auth::hasRole(ADMIN) === false) {
            $user->fetch();
            $post = $user->post; // Will be an empty array in case there are no posts.
            return $this->twig->display('dashboard.html.twig',['posts' => $post]);
        }
        $user->allPosts();
        $all = $user->post;
        $comment = new CommentModel();
        $comment->all();
        $comment->pendingComments();
        return $this->twig->display('dashboard.html.twig',["pend_comments" => $comment->pending_comments, "posts" => $all, "comments" => $comment->comments]);
    }

}
