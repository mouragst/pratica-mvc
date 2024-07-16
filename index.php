<?php

require "vendor/autoload.php";

use App\Utils\View;
use App\Http\Router;


define('URL', 'http://localhost:5000');

View::init([
    'URL' => URL,
]);

$router = new Router(URL);

include "routes/pages.php";

$router->run()->sendResponse();

