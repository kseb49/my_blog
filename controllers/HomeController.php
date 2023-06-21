<?php

namespace controllers;

use \core\Controller;
use models\BlogModel;

class HomeController extends Controller
{
    public function index(){
        $posts = new BlogModel($this->connect());
        $posts = $posts->home();
        $this->twig->display('home.twig',['posts'=>$posts]);
    }
}
