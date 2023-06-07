<?php
define('ROOT',dirname(__DIR__));
require(ROOT.'/vendor/autoload.php');
use \core\Router;
try{
$router = new Router($_SERVER['REQUEST_URI']);
$router->register('/','HomeController/index');
$router->register('/blog','BlogController/index');
$router->register('/admin','AccessController/login');
$router->register('/process','FormController/process','POST');
$router->lead();
}
catch(Exception $e){
    $e->getMessage();
}

