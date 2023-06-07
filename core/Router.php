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
   
    private function setGet(string $parameters){
        $this->url_parameters = $this->request['query'];
        dump(preg_split('#\?=.*#',$this->url_parameters));
    }

    /**
     * Undocumented function
     *
     * @param string $method
     * @param string $request
     * @param string $path
     * @return void
     */
    public function register(string $url,string $action){

            $this->url[$url] = $action;
    //         if(isset($_GET) && !empty($_GET)){
    //          $this->grab_params()->lead();
    //        }
    //         if(isset($_POST) && !empty($_POST)){
    //             echo 'hello';
    //             die();
    //         }
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    // private function grab_params(){
    //     $params =$_SERVER['QUERY_STRING'];
    //             $params =preg_split('#[\?\&]+#',$params);
    //             $gets=[];
    //             $keys=[];
    //             foreach($params as $values){
    //                 $gets[]= explode('=',$values);
    //                 }
    //             foreach($gets as $values){
    //                 foreach($values as $key => $lines){
    //                     if($key == 0){
    //                         $keys[] = $lines;
    //                     }
    //                     else{
    //                         $parameters[] = $lines;
    //                     }
    //                 }
    //         } 
    //         $this->url_parameters = $parameters = array_combine($keys,$parameters);
    //         return $this;

    // }

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