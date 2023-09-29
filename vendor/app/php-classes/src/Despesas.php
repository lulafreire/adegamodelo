<?php

namespace App;
use App\DB\Sql;
use \PDO;

class Despesas {

    public $id;
    public $valor;
    public $descricao;
    public $data;

    public static function salva($valor, $descricao, $data){
        $salvaDespesa = (new Sql('despesas'))->insert([ 
            'valor'=>$valor,
            'descricao'=>$descricao,
            'data'=>$data
        ]);
    }

    public static function deleta(){

    }

    public static function atualiza(){

    }

    public static function sum12meses($where){        

        $soma = myArray((new Sql('despesas'))->select($where, null, null, null, 'SUM(valor) AS despesa_total ')->fetchColumn());
        if($soma==''){
            return 0;
        } else {
            return round($soma, 2);
        }
    }

    public static function getTopDespesas(){

        // Esvazia a tabela temporária Top5despesas
        $deleta = Top5::deleteDespesas(null);

        // Pesquisa todas as despesas lançados
        $sql = myarray((new Sql('despesas'))->select(null, 'descricao', null, null)->fetchAll(PDO::FETCH_CLASS,self::class));
        
        foreach ($sql as $key => $value) {
            
            $descricao = $value['descricao'];

            // Soma o valor  de cada despesa localizado
            $vlr = (new Sql('despesas'))->select('descricao = "'.$descricao.'"', null, null, null, 'SUM(valor) AS total ')->fetchColumn();

            // Grava as informações no banco de dados temporário
            $grava = Top5::createDespesas($descricao, $vlr);
        }
        
        // Pesquisa orndenando pela despesa de maior valor
        $topDespesas = myarray((new Sql('top5despesas'))->select(null, null, 'valor DESC', 5,)->fetchAll(PDO::FETCH_CLASS,self::class));

           foreach ($topDespesas as $key => $value) {
           $descricao = $value['descricao'];          
           $valor = $value['valor'];

           $top5despesas[] = [           
            'descricao'=>$descricao,
            'valor'=>$valor
           ];
        } 
               
        return $topDespesas;
    } 
}