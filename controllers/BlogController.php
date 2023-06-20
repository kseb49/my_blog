<?php 
namespace controllers;

use Exception;
use PDOException;
use \core\Controller;
use models\BlogModel;
use models\CommentModel;

class BlogController extends Controller{

    public function index(){
      $datas = new BlogModel($this->connect());
      $datas = $datas->index();
      $this->twig->display('blog.twig',['datas' => $datas]);
    }

    public function single(array $params){
      $datas = new BlogModel($this->connect());
      $datas = $datas->single($params[0]);
      $comments = new Commentmodel($this->connect());
      $comments = $comments->fetch($params[0]);
      $this->twig->display('post.twig',['datas' => $datas,'comments'=>$comments]);
    }
}