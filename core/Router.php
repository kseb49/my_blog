<?php
namespace core;


class Router {

    /**
     *
     * @var array
     */
    protected $url_parameters = [];

    protected $post_parameters = [];
    
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
        if(array_key_exists('query',$this->request)){
            $this->setGet($this->request['query']);
        }
    }
    
    /**
     * Undocumented function
     *
     * @param string $method
     * @param string $request
     * @param string $path
     * @return void
     */
    public function register(string $url,string $action,?string $method = 'GET'){

            if($method === 'POST'){
                $this->setPosts($_POST);
            }
            $this->url[$url] = $action;
    
    }
    
    private function setGet(string $parameters){
        $names = [];
        $parameters = preg_split('#[=\&]+#',$parameters);
        foreach($parameters as $key => $values){
            if($key===0 || $key%2 === 0){
                $names[] = $values;
            }
            else{
                $this->url_parameters[] = $values;
            }
        } $this->url_parameters = array_combine($names,$this->url_parameters); 
    }

    private function setPosts(array $post_parameters){
        $this->post_parameters [] = $post_parameters;
    }

    public function lead(){
        if(array_key_exists($this->request['path'],$this->url)){
            $render =  explode('/',$this->url[$this->request['path']]);
            $controller = '\controllers\\'.$render[0];
            $action = $render[1];
            $display = new $controller;
            $display->$action();
        }
        throw new \Exception('404');
    }
}