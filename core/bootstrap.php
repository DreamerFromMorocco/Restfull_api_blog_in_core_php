<?php

require __DIR__.'/DB.php';  // ... setting up stuff
require __DIR__.'/Router.php';
require __DIR__.'/../routes.php';
require __DIR__ .'/../config.php';

$router = new Router;
$router->setRoutes($routes);

$url = $_SERVER['REQUEST_URI'];

//echo ( __DIR__."/../api/".$router->getFilename($url));
require __DIR__."/../api/".$router->getFilename($url); // serving a page  bases on url
?>