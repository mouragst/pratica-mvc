<?php

require "vendor/autoload.php";

use App\Controller\Pages\Home;
use App\Http\Response;
use App\Http\Router;

define('URL', 'http://localhost:5000');

$router = new Router(URL);

$router->get('/', [
    function() {
        return new Response(200, Home::getHome());
    }
]);

$router->run()->sendResponse();

