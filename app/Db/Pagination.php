<?php

namespace App\Db;

class Pagination {

    public $limit;
    public $results;
    public $pages;
    public $currentPage;

    public function __construct($results, $currentPage = 1, $limit = 10) {
        $this->limit = $limit;
        $this->results = $results;
        $this->currentPage = (is_numeric($currentPage) & $currentPage > 0) ? $currentPage : 1;
        $this->calculate();
    }

    private function calculate() {
        $this->pages = $this->results > 0 ? ceil($this->results / $this->limit) : 1;
        $this->currentPage = $this->currentPage <= $this->pages ? $this->currentPage : $this->pages;
        return $this->pages;
    }

    public function getLimit() {
        $offset = ($this->limit * ($this->currentPage - 1));
        return $offset.', '.$this->limit;
    }

    public function getPages() {
        if ($this->pages == 1) return [];

        $paginas = [];

        for ($i = 1; $i<$this->pages; $i++) {
            $paginas[] = [
                'page' => $i,
                'current' => $i == $this->currentPage,
            ];
        };

        return $paginas;
    }
}
