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
            'description' => $organization->description,
            'site' => $organization->site,
        ]);

        // VIEW DA PAGINA
        return parent::getPage('Pagina Teste', $content);
    }
}