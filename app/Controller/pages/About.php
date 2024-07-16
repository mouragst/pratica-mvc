<?php

namespace App\Controller\Pages;

use App\Model\Entity\Organization;
use App\Utils\View;

class About extends Page {

    /**
     * Método responsável por retornar o conteúdo (view) da home
     * @return string
     */
    public static function getAbout() {
        $organization = new Organization;

        // VIEW DO SOBRE
        $content = View::render('pages/about', [
            'name' => $organization->name,
            'description' => $organization->description,
            'site' => $organization->site,
        ]);

        // VIEW DA PAGINA
        return parent::getPage('About', $content);
    }
}