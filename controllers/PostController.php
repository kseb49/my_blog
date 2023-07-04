<?php

namespace controllers;

use utils\Pik;
use utils\Flash;
use core\Controller;
use models\PostModel;

class PostController extends Controller

{
    protected array $datas;

    public function createPostForm(){
        if($this->isUser()) {
            return $this->twig->display('edit-post.twig');
        }
    }

    /**
     * Create a new Post
     *
     * @param array $datas $_POST
     * @return void
     */
    public function createPost(array $datas){
        if($this->isUser()) {
            if($datas['#token'] !== $_SESSION['user']['token']){
               Flash::flash('danger','Vous ne pouvez pas créer d\'article');
                $this->redirect('dashboard');
            }
            unset($_POST['MAX_FILE_SIZE']);
            $this->datas = $_POST;
            if(isset($_FILES['image'])) {
                $image = new Pik($_FILES);
                if($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    if(is_int($image->check())){
                        $image->uploadErrors($image->check());
                    }
                    $newPost = new PostModel();
                    if($newPost->loadDatas($this->datas)->validate()) {
                        if($newPost->createPost($image->_name)) {
                            Flash::flash('success','Votre article est en ligne');
                            $this->redirect('dashboard');
                        }
                    }
                }
                $image->uploadErrors($_FILES['image']['error']);
            }
        }
    }
    

    /**
     * get the post to edit
     *
     * @param string $id
     * @return void
     */
    public function postToEdit(string $id){
        if($this->isUser()){
            $post = new PostModel();
            if($post->postToEdit($id)){
                return $this->twig->display('edit-post.twig',["post"=>$post->post,"action"=>"edit"]);
            }
            Flash::flash('danger',"Impossible de modifier cet  article");
            $this->redirect('dashboard');
        }
        Flash::flash('danger',"Connectez vous pour accéder à cette fonction");
        $this->redirect('inscription');
    }

    /**
     * Edit a post
     *
     * @param array $datas $_POST
     * @return void
     */
     public function postEdit(array $datas){
        if($this->isUser()) {
            if($datas['#token'] !== $_SESSION['user']['token']){
                Flash::flash('danger','Vous ne pouvez pas modifier cet article');
                $this->redirect('dashboard');
            }
            if(isset($_FILES['image'])) {
                unset($_POST['MAX_FILE_SIZE']);
                $this->datas = $_POST;
                $post = new PostModel();
                if($post->loadDatas($this->datas)->validate()) {    
                    if($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $image = new Pik($_FILES);
                    if(is_int($image->check())){
                        $image->uploadErrors($image->check());
                    }
                    if($post->postEdit($this->datas,$image->_name)) {
                        if ($image->parker()){
                            Flash::flash('success','Votre article est modifié');
                            $this->redirect('dashboard');
                        }
                        Flash::flash('danger','erreur interne');
                        $this->redirect('dashboard');
                    }
                    Flash::flash('danger','erreur interne,veuillez réessayer');
                    $this->redirect('dashboard');
                    }
                    if($_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
                        if($post->postEdit($this->datas)) {
                            Flash::flash('success','Votre article est modifié');
                            $this->redirect('dashboard');
                        }
                        Flash::flash('danger','erreur interne');
                        $this->redirect('dashboard');
                    }
                    Flash::flash('danger',$image->uploadErrors($_FILES['image']['error']));
			        $this->redirect('dashboard');
                }
            }
            Flash::flash('danger','aucun fichier image reçu');
            $this->redirect('dashboard');
        }
        $this->redirect('inscription');
    }

    
    public function deletePost(array $id){
        if($this->isUser()){
            if($id[1] !== $_SESSION['user']['token']){
                Flash::flash('danger','impossible de supprimer cet article');
                $this->redirect('dashboard');
            }
            $post = new PostModel();
            if($post->delete($id[0])){
                Flash::flash('success','Votre article est supprimé');
                $this->redirect('dashboard');
            }
        }
        $this->redirect('inscription');
    }

}
