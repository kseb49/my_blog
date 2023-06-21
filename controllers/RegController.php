<?php

namespace controllers;

use core\Controller;

class RegController extends Controller
{
    public function form(){
        $this->twig->display('registration.twig');
    }
}
