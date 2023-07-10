<?php 
namespace controllers;

use \core\Controller;
use models\BlogModel;
use models\CommentModel;
use utils\Flash;

class BlogController extends Controller{

    public function index(){
      $datas = new BlogModel();
      $datas = $datas->index();
      return $this->twig->display('blog.twig',['datas' => $datas]);
    }

    /**
     * Get a single post
     *
     * @param string $id
     * @return void
     */
    public function single(string $id){
      $datas = new BlogModel();
      $datas = $datas->single($id);
      $comments = new Commentmodel();
      $comments->fetch($id);
      return $this->twig->display('post.twig',['datas' => $datas,'comments'=> $comments->comments]);
  }
}