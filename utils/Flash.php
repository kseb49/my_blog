<?php 
namespace utils;

use core\Controller;

class Flash extends Controller
{
    static function flash(string $type,string $message){
        $_SESSION['flash'] = [$type => $message];
    }
}
