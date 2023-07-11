<?php

namespace controllers;

use \core\Controller;
use models\BlogModel;

class HomeController extends Controller
{
    public function index(){
        $posts = new BlogModel();
        $posts = $posts->home();
        return $this->twig->display('home.twig',['posts'=>$posts]);
    }
}
