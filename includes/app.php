<?php

require __DIR__."/../vendor/autoload.php";

use App\Utils\View;
use WilliamCosta\DotEnv\Environment;
use App\Http\Middleware\Queue as MiddlewareQueue;

Environment::load(__DIR__.'/../');

define('URL', getenv('URL'));

View::init([
    'URL' => URL,
]);

MiddlewareQueue::setMap([
    'maintenance' => App\Http\Middleware\Maintenance::class
]);

MiddlewareQueue::setDefault([
    'maintenance'
]);