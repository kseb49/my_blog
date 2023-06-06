<?php
// namespace core;

class Router {

    /**
     *
     * @var array
     */
    protected $url_parameters = [];

    /**
     *
     * @var string
     */
    protected string $url;

    public function __construct()
    {
        $this->url = preg_replace('#\?.*#','',$_SERVER ["REQUEST_URI"]);
    }

    /**
     * Undocumented function
     *
     * @param string $method
     * @param string $request
     * @param string $path
     * @return void
     */
    public function lead(string $method,string $request,string $path){

            if($request == $this->url){
                require 
            }




        try{
            if($method == 'GET' && !empty($_GET)){
             $this->grab_params();
           }
            if($method == 'POST'){
            if(isset($_POST) && !empty($_POST)){
                echo 'hello';
                die();
            }
            throw new Exception('Parametres non reçues');
           }
            // $this->url = preg_replace('#\?.*#','',$_SERVER ["REQUEST_URI"]);

        }
        catch(Exception $e){
                die($e->getMessage());
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function grab_params(){
        $params =$_SERVER['QUERY_STRING'];
                $params =preg_split('#[\?\&]+#',$params);
                $gets=[];
                $keys=[];
                foreach($params as $values){
                    $gets[]= explode('=',$values);
                    }
                foreach($gets as $values){
                    foreach($values as $key => $lines){
                        if($key == 0){
                            $keys[] = $lines;
                        }
                        else{
                            $parameters[] = $lines;
                        }
                    }
            } 
            $this->url_parameters = $parameters = array_combine($keys,$parameters);
            return $this;

    }
}


