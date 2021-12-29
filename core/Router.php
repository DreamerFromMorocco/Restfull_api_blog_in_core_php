<?php

class Router {

    private $routes = [];
    
    function setRoutes(Array $routes) {
        $this->routes = $routes;
       // echo json_encode($this->routes);
    }

    function getFilename(string $url) {
        foreach($this->routes as $route => $file) {
            if(strpos($url, $route) !== false){      
                return $file;
            }
        }
    }
}
