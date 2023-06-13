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
    protected $url = [];
        
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
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function lead(){
        $route = new Route($this->request,$this->url);
        if($route->search()){
            $controller = '\controllers\\'.$route->route[0];
            $action = $route->route[1];
            $display = new $controller;
            if(!empty($route->params)){
                $display->$action($route->params);
                return;
            }
            else{
                $display->$action();
                return;
            }
        }
            throw new Exception('404');
    }
}