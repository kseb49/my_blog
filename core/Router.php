<?php
namespace core;

use core\Auth;
use Exception;

class Router {

    
    /**
     * User request
     *
     * @var array
     */
    protected array $request;

    /**
     * routes list
     *
     * @var array
     */
    public $url = [];
        
        public function __construct(string $request)
        {
            $this->request = parse_url($request);
        }
        
    /**
     * Register a GET route 
     *
     * @param string $url
     * @param array $path
     * @return void
     */
    public function get(string $url,array $path){
        if(preg_match('#:{(\w+)}#',$url)){
            $url= preg_replace("#:{(\w+)}#",'([^/]+)',$url);
        }
        $this->url['GET'][$url] = $path;
        return $this;
    }
    /**
    * Register a post route
    *
    * @param string $url
    * @param string $action
    * @return void
    */
    public function post(string $url,array $path){
            
            $this->url['POST'][$url] = $path;
            return $this;
    }
   
    /**
     * Call the controller which  matches the query
     *
     * @return void
     */
    public function find(){
        $route = new Route($this->request,$this->url);
        if($route->search()){
            if(key_exists("role",$route->route)){
                if(!Auth::isAuthorize($route->route["role"])) {// same as == false
                    throw new Exception("Vous n'êtes pas autorisé à visiter cette page :".$this->request['path']."");
                    }
                }
                    $controller = '\controllers\\'.$route->route[0];
                    $action = $route->route[1];
                    $display = new $controller;
                    if(!empty($route->params)) {
                        if(count($route->params) === 1) {
                            $display->$action($route->params[0]);
                            return;
                        }
                        $display->$action($route->params);
                        return;
                    }
                    if(isset($_GET) && !empty($_GET)) {
                        $display->$action($_GET);
                        return;
                    }
                    if(isset($_POST) && !empty($_POST)) {
                        $display->$action($_POST);
                        return;
                    }
                    $display->$action();
                    return;
                
            }
        
            throw new Exception('Cette adresse est introuvable');
    }
}