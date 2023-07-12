<?php

namespace controllers;

use core\Auth;
use utils\Pik;
use utils\Flash;
use core\Controller;
use Exception;
use models\PostModel;
use models\UserModel;

class PostController extends Controller

{
    protected array $datas;

    public function createPostForm(){
        if(Auth::getRole(ADMIN)){
            $users = new UserModel();
            $users->users();
            return $this->twig->display('edit-post.twig',['users'=>$users->user]);
        }
        return $this->twig->display('edit-post.twig');
        }

    /**
     * Create a new Post
     *
     * @param array $datas $_POST
     * @return void
     */
    public function createPost(array $datas){
        try{
            if($datas['#token'] !== $_SESSION['user']['token']) {
                throw new Exception("Vous ne pouvez pas créer d\'article");
            }
            unset($_POST['MAX_FILE_SIZE']);
            $this->datas = $_POST;
            if(isset($_FILES['image'])) {
                $image = new Pik($_FILES);
                if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                    throw new Exception($image->uploadErrors($_FILES['image']['error']));
                }
                if(is_int($image->check())) {// an integer return, means an error
                    throw new Exception($image->uploadErrors($image->check()));
                }
                $newPost = new PostModel();
                if($newPost->loadDatas($this->datas)->validate()) {
                    if($newPost->createPost($image->_name)) {
                        if($image->parker()) {
                            Flash::flash('success','Votre article est en ligne');
                            $this->redirect('dashboard');
                        }
                        throw new Exception("Une erreur est survenue, merci de réessayer - SAVE IMG");
                    }
                    throw new Exception("Une erreur est survenue, merci de réessayer");
                }
            }
            throw new Exception("Vous devez fournir une image pour l'article");
        }catch(Exception $e) {
            Flash::flash('danger',$e->getMessage());
            $this->redirect(REF);
        }
    }
    
    /**
     * get the post to edit
     *
     * @param string $id
     * @return void
     */
    public function postToEdit(string $id) {
        try {
            $post = new PostModel();
            if($post->postToEdit($id)){
                if(Auth::getRole(ADMIN)){
                    $users = new UserModel();
                    if($users->users()) {
                        return $this->twig->display('edit-post.twig',["post"=>$post->post,"action"=>"edit","users"=> $users->user]);
                    }
                    throw new Exception("Problème de récupération de données - USERS FAIL");
                }
                return $this->twig->display('edit-post.twig',["post"=>$post->post,"action"=>"edit"]);
            }
            throw new Exception("L'article n'a pas été trouvé");
        }catch(Exception $e) {
            Flash::flash('danger',$e->getMessage());
            $this->redirect('dashboard');
        }
        
    }


    /**
     * Edit a post
     *
     * @param array $datas $_POST
     * @return void
     */
     public function postEdit(array $datas) {
        try {
             if($datas['#token'] !== $_SESSION['user']['token']){
                throw new Exception('Vous ne pouvez pas modifier cet article');
            }
            if(isset($_FILES['image'])) {
                unset($_POST['MAX_FILE_SIZE']);
                $this->datas = $_POST;
                $post = new PostModel();
                if($post->loadDatas($this->datas)->validate()) {    
                    $image = new Pik($_FILES);
                    if($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                        if(is_int($image->check())) {// an integer return, means an error
                            throw new Exception($image->uploadErrors($image->check()));
                        }
                        if($post->postEdit($this->datas,$image->_name)) {
                            if ($image->parker()){
                                Flash::flash('success','Votre article est modifié');
                                $this->redirect('dashboard');
                            }
                            throw new Exception('Erreur interne - IMG REC');
                        }
                        throw new Exception("L'article n'a pas été modifié");
                    }
                    if($_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
                        if($post->postEdit($this->datas)) {
                            Flash::flash('success','Votre article est modifié');
                            $this->redirect('dashboard');
                        }
                        throw new Exception("L'article n'a pas été modifié");
                    }
                    throw new Exception($image->uploadErrors($_FILES['image']['error']));
                }
            }
           throw new Exception("aucun fichier image reçu");
           
        }catch(Exception $e) {
            Flash::flash('danger',$e->getMessage());
            $this->redirect('dashboard');
        }
           
    }

    
    public function deletePost(array $id){
        try {
            if($id[1] !== $_SESSION['user']['token']){
                throw new Exception("Impossible de supprimer cet article");
            }
            $post = new PostModel();
            if($post->delete($id[0])){
                Flash::flash('success','Votre article est supprimé');
                $this->redirect('dashboard');
            }
            throw new Exception("Impossible de supprimer cet article");
        } catch (Exception $e) {
            Flash::flash('danger',$e->getMessage());
            $this->redirect('dashboard');
        }
    }

}
