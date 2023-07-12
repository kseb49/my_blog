<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
!defined('ROOT') ? define('ROOT',dirname(__DIR__)) : null;
!defined('PARAMS') ? define('PARAMS',preg_replace("#/#",DIRECTORY_SEPARATOR,dirname(__DIR__).'/env.dev.json')) : null;
!defined('USER') ? define('USER', 0) : null;
!defined('ADMIN') ? define('ADMIN', 1) : null;
require(ROOT.'/vendor/autoload.php');

use \core\Router;
use utils\Flash;

try{
   $router = new Router($_SERVER['REQUEST_URI']);

   $router->get('/',['HomeController','index']);
   $router->get('/blog',['BlogController','index']);
   $router->get('/blog/:{id}',['BlogController','single']);
   $router->get('/blog/edit-comment/:{id}',['CommentController','getComment',"role"=>USER]);
   $router->post('/edit-comment',['CommentController','editComment',"role"=>USER]);
   $router->get('/blog/delete-comment/:{id}/:{token}',['CommentController','deleteComment',"role"=>USER]);
  //  $router->get('/blog/:{cat}/:{id}',['BlogController','group']);

   $router->post('/commentaire',['CommentController','create',"role"=>USER]);

   $router->get('/to-moderate',['CommentController','commentsLists',"role"=>ADMIN]);
   $router->get('/allcomments',['CommentController','allComments',"role"=>ADMIN]);
   $router->get('/accept/:{id}/:{token}/:{id}',['CommentController','accept',"role"=>ADMIN]);
   $router->get('/reject/:{id}/:{token}/:{id}',['CommentController','reject',"role"=>ADMIN]);

   $router->get('/inscription',['RegisterUserController','form']);
   $router->post('/inscription',['RegisterUserController','register']);
   $router->get('/validation-mail',['RegisterUserController','validateFromMail']);

   $router->post('/connexion',['UserController','logIn']);
   $router->get('/deconnexion',['UserController','logOut',"role"=>USER]);

   $router->get('/dashboard',['UserController','dashboard',"role"=>USER]);
   $router->get('/creation',['PostController','createPostForm',"role"=>USER]);
   $router->post('/creation',['PostController','createPost',"role"=>USER]);
   $router->get('/edition/:{id}',['PostController','postToEdit',"role"=>USER]);
   $router->post('/post-edit',['PostController','postEdit',"role"=>USER]);
   $router->get('/delete/:{id}/:{token}',['PostController','deletePost',"role"=>USER]);

   $router->find();

}
catch(Exception $e){
  Flash::flash('danger',$e->getMessage());
  header("Location: http://blog.test/");
}

