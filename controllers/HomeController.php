<?php

namespace controllers;

use \core\Controller;

class HomeController extends Controller
{
    public function index(){
        
        $this->twig->display('accueil.html.twig');
    }
}