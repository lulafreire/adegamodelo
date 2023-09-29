<?php

namespace App;
use \App\DB\Sql;
use PDO;

class Categorias {

    public $id_categoria;
    public $nome_categoria;
    public $imagem_categoria;

    public static function getAll(){
        $sql = myArray((new Sql('categorias'))->select()->fetchAll(PDO::FETCH_CLASS,self::class));

        return $sql;
    }

}
