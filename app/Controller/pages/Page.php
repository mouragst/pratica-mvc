<?php

namespace App\Controller\Pages;

use App\Utils\View;

class Page {

    /**
     * Método responsável por renderizar o header da pagina
     * @return string
     */
    private static function getHeader() {
        return View::render('pages/header');
    }

    /**
     * Método responsável por renderizar o footer da pagina
     * @return string
     */
    private static function getFooter() {
        return View::render('pages/footer');
    }

    public static function getPagination($request, $pagination) {
        $pages = $pagination->getPages();

        if (count($pages) <= 1) return '';

        $links = '';

        $url = $request->getRouter()->getCurrentUrl();

        $queryParams = $request->getQueryParams();

        foreach ($pages as $page) {
            $queryParams['page'] = $page['page'];
            $link = $url.'?'.http_build_query($queryParams);
            $links .= View::render('pages/pagination/link', [
                'page' => $page['page'],
                'link' => $link,
                'active' => $page['current'] ? 'active' : '',
            ]);
        }
        return View::render('pages/pagination/box', [
                'links' => $links,
            ]);
    }

    /**
     * Método responsável por retornar o conteúdo [view] da home
     * @return string
     */
    public static function getPage($title, $content) {
        return View::render('pages/page', [
            'title' => $title,
            'header' => self::getHeader(),
            'content' => $content,
            'footer' => self::getFooter(),
        ]);
    }
}