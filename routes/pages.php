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

// ROTA DINÂMICA
$router->get('/pagina/{idPagina}/{acoes}', [
    function($idPagina, $acoes) {
        return new Response(200, 'Pagina '.$idPagina.' - '.$acoes);
    }
]);