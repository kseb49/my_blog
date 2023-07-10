<?php
namespace core;

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
    // public function auth(string $role){
            
    //     array_push($this->url[[array_key_last($this->url[$_SERVER['REQUEST_METHOD']])]],['auth'=>$role]);dd($this->url);
    //         return $this;
    // }
    /**
     * Call the controller which  matches the query
     *
     * @return void
     */
    public function find(){
        $route = new Route($this->request,$this->url);
        if($route->search()){
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