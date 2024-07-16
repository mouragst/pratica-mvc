<?php

namespace App\Controller\Pages;

use App\Model\Entity\Organization;
use App\Utils\View;

class Home extends Page {

    /**
     * Método responsável por retornar o conteúdo (view) da home
     * @return string
     */
    public static function getHome() {
        $organization = new Organization;

        // VIEW DA HOME
        $content = View::render('pages/home', [
            'name' => $organization->name,
        ]);

        // VIEW DA PAGINA
        return parent::getPage('Home Page', $content);
    }
}