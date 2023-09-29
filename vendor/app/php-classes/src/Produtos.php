<?php

namespace App;

use App\DB\Sql;
use \PDO;

class Produtos{

    public $id;
    public $codigo;
    public $imagem;
    public $categoria;
    public $preco_custo;
    public $preco_venda;
    public $estoque;
    public $fornecedor;

    public static function getByID($id){
        $sql = myArray((new Sql('produtos'))->select('id = '.$id)->fetchAll(PDO::FETCH_CLASS,self::class));
        $count = count($sql);

        return $sql;

    }

    public static function getAll(){
        $sql = myArray((new Sql('produtos'))->select(NULL, NULL, "nome")->fetchAll(PDO::FETCH_CLASS,self::class));

        return $sql;
    }

    public static function getByCategoria($categoria){
        $sql = myArray((new Sql('produtos'))->select('categoria = '.$categoria)->fetchAll(PDO::FETCH_CLASS,self::class));
        $count = count($sql);

        return $sql;
    }

    public static function getByNome($produto){
        $sql = myArray((new Sql('produtos'))->select('nome LIKE "%'.$produto.'%"')->fetchAll(PDO::FETCH_CLASS,self::class));
        $count = count($sql);

        if($count!=0){
            return $sql;
        } else {
            return $count;
        }
        
    }

}

