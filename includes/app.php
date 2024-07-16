<?php

require __DIR__."/../vendor/autoload.php";

use App\Utils\View;
use WilliamCosta\DotEnv\Environment;

Environment::load(__DIR__.'/../');

define('URL', getenv('URL'));

View::init([
    'URL' => URL,
]);
