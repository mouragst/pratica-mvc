<?php

namespace App\Model\Entity;

use App\Db\Database;

class Testimony {

    public $id;
    public $name;
    public $message;
    public $date;

    public function cadastrar() {
        $database = new Database('depoimentos');
        $this->date = date('Y-m-d H:i:s');
        $this->id = $database->insert([
            'name' => $this->name,
            'message' => $this->message,
            'date' => $this->date,
        ]);

        return true;
    }
    
    public static function getTestimonies($where = null, $order = null, $limit = null, $fields = '*') {
        return (new Database('depoimentos'))->select($where, $order, $limit, $fields);
    }
}