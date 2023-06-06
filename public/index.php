<?php
define('ROOT',dirname(__DIR__));
define('REQUEST',$_SERVER['REQUEST_URI']);
require (ROOT.'/core/Router.php');
require(ROOT.'/vendor/autoload.php');
$loader = new \Twig\Loader\FilesystemLoader(ROOT.'/views');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);
$router = new Router();
$router->lead('GET','/','accueil');
// $router->lead('POST','formulaire','accueil');