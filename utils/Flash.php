<?php
namespace utils;

use core\Controller;

class Flash extends Controller
{
    /**
     * Set a message in session
     *
     * @param string    $type danger or success (depends on class use in css)
     * @param string    $message 
     * @return void
     */
    static function flash(string $type, string $message) {
        $_SESSION['flash'] = [$type => $message];
    }

}
