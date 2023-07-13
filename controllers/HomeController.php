<?php

namespace controllers;

use \core\Controller;
use models\BlogModel;

class HomeController extends Controller
{


    /**
     * Display the Home page
     *
     * @return void
     */
    public function index()
    {
        $posts = new BlogModel();
        $posts = $posts->home();
        return $this->twig->display('home.twig',['posts' => $posts]);

    }


}
