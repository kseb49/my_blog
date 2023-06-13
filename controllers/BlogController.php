<?php 
namespace controllers;

use \core\Controller;

class BlogController extends Controller{

    public function index(){
       $this->twig->display('blog.twig');
    }
    public function single(array $params){
      $this->twig->display('post.twig');
    }
}