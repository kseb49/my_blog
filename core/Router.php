<?php
// namespace core;

class Router {

    public function lead(string $method,string $request,string $path){
        
        //cas ou il y a des variables en get ?foo=bar&bar=foo
            if(!empty($_GET)){
                $params =$_SERVER['QUERY_STRING'];
                // echo $params;
                // var_dump(preg_split('#[\?\&]+#',$params));
                // die();
                $params =preg_split('#[\?\&]+#',$params);
                $gets=[];
                $parameters=[];
                foreach($params as $values){
                    $gets[]= explode('=',$values);
                    }
                    echo '<pre>';
                    var_dump(array_values($gets));echo '</pre>';die();
            }
            foreach($gets as $values){
                foreach($values as $lines){
                    
                }
            }
            var_dump($_SERVER ["REQUEST_URI"]);
            die();
            
        
           
}
}

