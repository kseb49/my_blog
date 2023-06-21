<?php 
namespace controllers;

use \core\Controller;
use models\BlogModel;
use models\CommentModel;

class BlogController extends Controller{

    public function index(){
      $datas = new BlogModel();
      $datas = $datas->index();
      $this->twig->display('blog.twig',['datas' => $datas]);
    }

    public function single(array $params){
      $datas = new BlogModel();
      $datas = $datas->single($params[0]);
      $comments = new Commentmodel();
      $comments = $comments->fetch($params[0]);
      $this->twig->display('post.twig',['datas' => $datas,'comments'=>$comments]);
    }
}