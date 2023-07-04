<?php 
namespace controllers;

use \core\Controller;
use models\BlogModel;
use models\CommentModel;

class BlogController extends Controller{

    public function index(){
      $datas = new BlogModel();
      $datas = $datas->index();
      return $this->twig->display('blog.twig',['datas' => $datas]);
    }

    public function single(string $params){
      $datas = new BlogModel();
      $datas = $datas->single($params);
      $comments = new Commentmodel();
      $comments = $comments->fetch($params);
      return $this->twig->display('post.twig',['datas' => $datas,'comments'=>$comments]);
    }
}