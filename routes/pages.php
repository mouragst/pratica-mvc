<?php

use \App\Http\Response;
use \App\Controller\Pages;


// ROTA HOME
$router->get('/', [
    function() {
        return new Response(200, Pages\Home::getHome());
    }
]);

// ROTA SOBRE
$router->get('/sobre', [
    function() {
        return new Response(200, Pages\About::getAbout());
    }
]);

// ROTA DEPOIMENTOS
$router->get('/depoimentos', [
    function($request) {
        return new Response(200, Pages\Testimony::getTestimonies($request));
    }
]);

// ROTA DEPOIMENTOS (INSERT)
$router->post('/depoimentos', [
    function($request) {
        return new Response(200, Pages\Testimony::insertTestimony($request));
    }
]);