<?php
namespace core;

use Exception;



class Route extends Router
{
    public array $route = [];
    public array $params = [];
    
    public function __construct(array $request,array $url){
        $this->request = $request;
        $this->url= $url;
    }

    protected function search():bool{
      
        if(preg_match('#^/(\w+)/(.+)$#',$this->request['path'])){
            foreach($this->url[$_SERVER['REQUEST_METHOD']] as $key => $value){
                 if(preg_match('#^'.$key.'$#',$this->request['path'],$matches)){
                   array_shift($matches);
                   $matches  ['call'] = [explode('/',$value)];
                   $this->route = $matches;
                   return true;
            }}
         }

        if(array_key_exists($this->request['path'],$this->url[$_SERVER['REQUEST_METHOD']])){
            $render= $this->url[$_SERVER['REQUEST_METHOD']][$this->request['path']];
            $render =  explode('/',$render);

            $this->route = $render;
            return true;
    }
    throw new Exception('pas de route correspondante');
    }
    // private static function getParams():array{

    //     if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST) && !empty($_POST)){
    //         return $_POST;
    //     }
    //     elseif( $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET) && !empty($_GET)){
    //        return $_GET;
    //     }
    //     else{
    //         throw new Exception('Parameters expected');
    //     }
        
    // }





}




