<?php

namespace App\Controller\Pages;

use App\Db\Pagination;
use App\Utils\View;
use App\Model\Entity\Testimony as EntityTestimony;

class Testimony extends Page {

    private static function getTestimonyItems($request, &$pagination) {

        // PAGINAÇÃO
        $queryParams = $request->getQueryParams();
        $quantidadeTotal = EntityTestimony::getTestimonies(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        $paginaAtual = $queryParams['page'] ?? 1;
        $pagination = new Pagination($quantidadeTotal, $paginaAtual, 1);

        // IMPRIME DEPOIMENTOS
        $itens = '';
        $results = EntityTestimony::getTestimonies(null, 'id DESC', $pagination->getLimit());
        
        while ($testimony = $results->fetchObject(EntityTestimony::class)) {
            $itens .= View::render('pages/testimony/item', [
                'name' => $testimony->name,
                'message' => $testimony->message,
                'date' => date('d/m/Y H:i:s',strtotime($testimony->date))
            ]);
        }

        return $itens;
    }

    /**
     * Método responsável por retornar o conteúdo (view) de depoimentos
     * @return string
     */
    public static function getTestimonies($request) {

        // VIEW DE DEPOIMENTOS
        $content = View::render('pages/testimonies', [
            'itens' => self::getTestimonyItems($request, $pagination),
            'pagination' => parent::getPagination($request, $pagination)
        ]);

        // VIEW DA PAGINA
        return parent::getPage('Depoimentos', $content);
    }

    public static function insertTestimony($request) {
        $postVars = $request->getPostVars();

        $testimony = new EntityTestimony;
        $testimony->name = $postVars['name'];
        $testimony->message = $postVars['message'];
        $testimony->cadastrar();
        
        return self::getTestimonies($request);
    }
}