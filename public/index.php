<?php
define('ROOT',dirname(__DIR__));
define('PARAMS',dirname(__DIR__).'/env.dev.json');
require(ROOT.'/vendor/autoload.php');

use \core\Router;

try{
   $router = new Router($_SERVER['REQUEST_URI']);
   $router->get('/',['HomeController','index']);
   $router->get('/blog',['BlogController','index']);
   $router->get('/blog/:{id}',['BlogController','single']);
   $router->get('/blog/:{cat}/:{id}',['BlogController','group']);
   $router->get('/commentaire',['CommentController','edit']);
   $router->get('/inscription',['RegisterUserController','form']);
   $router->post('/inscription',['RegisterUserController','register']);
   $router->get('/validation-mail',['RegisterUserController','validateFromMail']);
  //  $router->get('/inscription',['RegisterUserController','mailconfirmation']);
   $router->find();

}
catch(Exception $e){
  echo $e->getMessage();
}

