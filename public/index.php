<?php
define('ROOT',dirname(__DIR__));
require(ROOT.'/vendor/autoload.php');

use \core\Router;

try{
   $router = new Router($_SERVER['REQUEST_URI']);
   $router->get('/',['HomeController','index']);
   $router->get('/blog',['BlogController','index']);
   $router->get('/blog/:{id}',['BlogController','single']);
   $router->get('/blog/:{cat}/:{id}',['BlogController','group']);
   $router->get('/commentaire',['CommentController','edit']);
   $router->post('/process',['FormController','process']);
   $router->lead();
}
catch(Exception $e){
   echo $e->getMessage();
}

