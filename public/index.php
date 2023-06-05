<?php
define('ROOT',dirname(__DIR__));
define('REQUEST',$_SERVER['REQUEST_URI']);
require(ROOT.'/vendor/autoload.php');
$loader = new \Twig\Loader\FilesystemLoader(ROOT.'/views');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);
if(REQUEST == '/')
{
echo $twig->render('/accueil.html.twig');
}
else{
   echo '404';
}