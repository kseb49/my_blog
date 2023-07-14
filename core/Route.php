<?php
namespace core;

use Exception;

class Route extends Router
{

    /**
     * The controller and the method to call as defined in index
     *
     * @var array
     */
    public array $route = [];

    /**
     * The URL parameters
     *
     * @var array
     */
    public array $params = [];

    /**
     * $_SERVER['REQUEST_METHOD']
     *
     * @var string
     */
    public string $server_request;


    public function __construct(array $request, array $url)
    {
        $this->request = $request;
        $this->url = $url;
        $this->server_request = $_SERVER['REQUEST_METHOD'];

    }


    /**
     * Look for a match in the registered routes list
     *
     * @return boolean
     */
    protected function search():bool
    {
        /*
         * Seeks for a query with the given pattern ^/(\w+)/(.+)$ => /foo/bar , /foo/bar/foo .
         */
        if (preg_match('#^/(\w+)/(.+)$#', $this->request['path'])) {
            foreach ($this->url[$this->server_request] as $key => $value) {
                if (preg_match('#^'.$key.'$#', $this->request['path'], $matches)) {
                    array_shift($matches);
                    $this->route = $value;
                    $this->params = $matches;
                    return true;
                }
            }
        }
        if (array_key_exists($this->request['path'], $this->url[$this->server_request]) === true) {
            $this->route = $this->url[$this->server_request][$this->request['path']];
            return true;
        }
        throw new Exception($this->request['path'].'N\'est pas une route valide');

        }


}
