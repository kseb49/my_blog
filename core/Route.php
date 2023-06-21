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
    /**
     * Look for a match in the registered routes list
     *
     * @return boolean
     */
    protected function search():bool{
        /**
         * seeks for a query with a given pattern
         */
        if(preg_match('#^/(\w+)/(.+)$#',$this->request['path'])){
            foreach($this->url[$_SERVER['REQUEST_METHOD']] as $key => $value){
                 if(preg_match('#^'.$key.'$#',$this->request['path'],$matches)){
                   array_shift($matches);
                   $this->route = $value;
                   $this->params = $matches;
                   return true;
            }}
        }
        if(array_key_exists($this->request['path'],$this->url[$_SERVER['REQUEST_METHOD']])){
            $this->route = $this->url[$_SERVER['REQUEST_METHOD']][$this->request['path']];
            return true;
        }
        throw new Exception('pas de route correspondante');
        }
}
