<?php 
namespace controllers;

use utils\Flash;
use core\Controller;

class CommentController extends Controller{

    public function create(array $comment){
        if($comment['#token'] !== $_SESSION['user']['token']){
            Flash::flash('danger','Vous ne pouvez pas commenter');
            $this->redirect('dashboard');
         }
         
    }
}