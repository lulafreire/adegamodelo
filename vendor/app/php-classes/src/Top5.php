<?php

namespace App;

use App\DB\Sql;
use PDO;

Class Top5 {

    public $produto;
    public $quantidade;
    public $valor;

    public static function create($produto, $quantidade, $valor){

        $sql = (new Sql('top5'))->insert([
            'produto'=>$produto,
            'quantidade'=>$quantidade,
            'valor'=>$valor
        ]);

        return true;

    }

    public static function delete(){
        return (new Sql('top5'))->delete('produto !=""');
    }

    public static function createDespesas($descricao, $valor){

        $sql = (new Sql('top5despesas'))->insert([
            'descricao'=>$descricao,            
            'valor'=>$valor
        ]);

        return true;

    }

    public static function deleteDespesas(){
        return (new Sql('top5despesas'))->delete('descricao !=""');
    }

    
}

