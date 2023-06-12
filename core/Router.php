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
 * @param string $action
 * @param boolean|null $params
 * @return void
 */
    public function get(string $url,string $action){
            
            if(preg_match('#:{(\w+)}$#',$url)){
                $url= preg_replace("#:{(\w+)}$#",'([^/]+)',$url);
            }
            $this->url['GET'][$url] = $action;
            return;
      
}
/**
* Register a post route
*
* @param string $url
* @param string $action
* @return void
*/
    public function post(string $url,string $action){
            
            $this->url['POST'][$url] = $action;
}
    /**
     * Undocumented function
     *
     * @return void
     */
    public function lead(){
        $route = new Route($this->request,$this->url);
        if($route->search()){
            if()
            $controller = '\controllers\\'.$route->route[0];
            $action = $route[1];
            $display = new $controller;
            $display->$action();
        };
        // if(array_key_exists($this->request['path'],$this->url[$_SERVER['REQUEST_METHOD']])){
        //     // $render =  explode('/',$this->url[$_SERVER['REQUEST_METHOD']][$this->request['path']]);
        //     // $controller = '\controllers\\'.$render[0];
        //     // $action = $render[1];
        //     // $display = new $controller;
        //     // $params = $this->request['params'] ?? null;
        //     // $display->$action();
        // }
        
            throw new Exception('404');
       
             
       
    }
}