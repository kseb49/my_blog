<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
!defined('ROOT') ? define('ROOT',dirname(__DIR__)) : null;
!defined('PARAMS') ? define('PARAMS',preg_replace("#/#",DIRECTORY_SEPARATOR,dirname(__DIR__).'/env.dev.json')) : null;
require(ROOT.'/vendor/autoload.php');

use \core\Router;
use utils\Flash;

try{
   $router = new Router($_SERVER['REQUEST_URI']);

   $router->get('/',['HomeController','index']);
   $router->get('/blog',['BlogController','index']);
   $router->get('/blog/:{id}',['BlogController','single']);
  //  $router->get('/blog/:{cat}/:{id}',['BlogController','group']);
   $router->post('/commentaire',['CommentController','create']);
   $router->get('/to-moderate',['CommentController','commentsLists']);
   $router->get('/accept/:{id}/:{token}/:{id}',['CommentController','accept']);
   $router->get('/reject/:{id}/:{token}/:{id}',['CommentController','reject']);

   $router->get('/inscription',['RegisterUserController','form']);
   $router->post('/inscription',['RegisterUserController','register']);
   $router->get('/validation-mail',['RegisterUserController','validateFromMail']);

   $router->post('/connexion',['UserController','logIn']);
   $router->get('/deconnexion',['UserController','logOut']);

   $router->get('/dashboard',['UserController','dashboard']);
   $router->get('/creation',['PostController','createPostForm']);
   $router->post('/creation',['PostController','createPost']);
   $router->get('/edition/:{id}',['PostController','postToEdit']);
   $router->post('/post-edit',['PostController','postEdit']);
   $router->get('/delete/:{id}/:{token}',['PostController','deletePost']);

   $router->find();

}
catch(Exception $e){
  Flash::flash('danger',$e);
  header("Location: http://blog.test/");
}

