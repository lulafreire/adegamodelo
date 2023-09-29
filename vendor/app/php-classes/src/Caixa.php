<?php

namespace App;

use App\DB\Sql;
use \PDO;

class Caixa {

    public $id;
    public $data_abertura;
    public $data_fechamento;
    public $saldo_inicial;
    public $saldo_final;
    public $entradas;
    public $saidas;
    public $status;

    public static function abrir($valor){
        
        // Abre o caixa
        $abreCaixa = (new Sql('caixa'))->insert([
            'data_abertura'=>date('Y-m-d H:i:s'),
            'saldo_inicial'=>$valor,
            'saldo_final'=>$valor,
            'status'=>1
        ]);
                
    }

    public static function reabrir($saldo_final){
        $hoje = date('Y-m-d');

        // Atualiza o banco de dados caixa
        $atualiza = (new Sql('caixa'))->update('data_abertura LIKE "%'.$hoje.'%"',[           
            'status'=>1,
            'data_fechamento'=>"0000-00-00 00:00:00",
            'saldo_final'=>$saldo_final
        ]);  
    }

    public static function atualizar($valor){
        $hoje = date('Y-m-d');

        $caixa = myArray(Caixa::buscaPorDia($hoje));
        $entradas = $caixa[0]['entradas'];
        $saidas = $caixa[0]['saidas'];

        // Nova Soma
        $vlr_saldo_final = $valor + $entradas - $saidas;

        // Atualiza o banco de dados caixa
        $atualiza = (new Sql('caixa'))->update('data_abertura LIKE "%'.$hoje.'%"',[           
            'saldo_inicial'=>$valor,
            'saldo_final'=>$vlr_saldo_final
        ]);


    }

    public static function fechar($data_abertura, $conferencia, $diferenca, $observacao){
        // Atualiza o banco de dados caixa
        $atualiza = (new Sql('caixa'))->update('data_abertura LIKE "%'.$data_abertura.'%"',[                       
            'saldo_final'=>$conferencia,
            'diferenca'=>$diferenca,
            'obs'=>$observacao,
            'data_fechamento'=>date('Y-m-d H:i:s'),
            'status'=>2
        ]);
    }

    public static function entrada($pgto_dinheiro, $descricao){
        $hoje = date('Y-m-d');

        $caixa = myArray(Caixa::buscaPorDia($hoje));
        $entradas = $caixa[0]['entradas'];
        $saldo_final = $caixa[0]['saldo_final'];

        // Soma os valores
        $vlr_entrada = $entradas + $pgto_dinheiro;
        $vlr_saldo_final = $saldo_final + $pgto_dinheiro;

        // Atualiza o banco de dados caixa
        $entrada = (new Sql('caixa'))->update('data_abertura LIKE "%'.$hoje.'%"',[           
            'entradas'=>$vlr_entrada,
            'saldo_final'=>$vlr_saldo_final
        ]);

        // Grava no banco de dados caixa_entradas
        $salvaEntrada = (new Sql('caixa_entradas'))->insert([
            'data'=>date('Y-m-d H:i:s'),
            'valor'=>$pgto_dinheiro,
            'descricao'=>$descricao
        ]);

    }

    public static function saida($valor, $descricao){

        $hoje = date('Y-m-d');

        $caixa = myArray(Caixa::buscaPorDia($hoje));
        $saidas = $caixa[0]['saidas'];
        $saldo_final = $caixa[0]['saldo_final'];        

        // Soma os valores
        $vlr_saida = $saidas + $valor;
        $vlr_saldo_final = $saldo_final - $valor;

        //echo "<pre>"; print_r($descricao); echo "</pre>"; exit;

        // Atualiza o banco de dados caixa
        $saida = (new Sql('caixa'))->update('data_abertura LIKE "%'.$hoje.'%"',[           
            'saidas'=>$vlr_saida,
            'saldo_final'=>$vlr_saldo_final
        ]);

        // Grava no banco de dados caixa_saidas
        $salvaSaida = (new Sql('caixa_saidas'))->insert([
            'data'=>date('Y-m-d H:i:s'),
            'valor'=>$valor,
            'descricao'=>$descricao
        ]);

        switch ($descricao) {
            case 'Compra de Mercadoria':
                
                break;
            case 'Cofre':
                
                break;
            
            default:
               // Grava no banco de dados despesas
                $salvaDespesa = (new Sql('despesas'))->insert([
                    'data'=>date('Y-m-d H:i:s'),
                    'valor'=>$valor,
                    'descricao'=>$descricao
                ]);
                break;
        }
        
    }

    public static function buscaPorDia($data){
        $sql = myArray((new Sql('caixa'))->select('data_abertura LIKE "%'.$data.'%"')->fetchAll(PDO::FETCH_CLASS,self::class));
        $count = count($sql);
        if($count!=0){
            return $sql;
        } else {
            return 0;
        }
    }

    public static function caixa_aberto(){

        $hoje = date('Y-m-d');

        $sql = myArray((new Sql('caixa'))->select('data_abertura != "%'.$hoje.'%" AND data_fechamento LIKE "%0000%"')->fetchAll(PDO::FETCH_CLASS,self::class));
        $count = count($sql);
        if($count!=0){
            return $sql;
        } else {
            return 0;
        }
    }

    public static function fechamentos(){
        $hoje = date('Y-m-d');

        $sql = myArray((new Sql('caixa'))->select('data_abertura != "%'.$hoje.'%" AND status = 2', null, 'data_abertura DESC', 3)->fetchAll(PDO::FETCH_CLASS,self::class));
        $count = count($sql);
        if($count!=0){
            return $sql;
        } else {
            return 0;
        } 
    }

    public static function getEntradas(){
        $hoje = date('Y-m-d');

        $sql = myArray((new Sql('caixa_entradas'))->select('data LIKE "%'.$hoje.'%"', null, 'data DESC')->fetchAll(PDO::FETCH_CLASS,self::class));
        $count = count($sql);
        if($count!=0){
            return $sql;
        } else {
            return 0;
        }
    }

    public static function getSaidas(){
        $hoje = date('Y-m-d');

        $sql = myArray((new Sql('caixa_saidas'))->select('data LIKE "%'.$hoje.'%"', null, 'data DESC')->fetchAll(PDO::FETCH_CLASS,self::class));
        $count = count($sql);
        if($count!=0){
            return $sql;
        } else {
            return 0;
        }
    }

    public static function excluirSaida(){
        $hoje = date('Y-m-d');

        $caixa_saidas = (new Sql('caixa_saidas'))->delete('data LIKE "%'.$hoje.'%" AND descricao like "%'.$_GET['descricao'].'%" AND valor = '.$_GET['valor']);
        $despesas     = (new Sql('despesas'))->delete('data LIKE "%'.$hoje.'%" AND descricao like "%'.$_GET['descricao'].'%" AND valor = '.$_GET['valor']);

        // Ajustar o saldo em caixa
        $caixa = Caixa::buscaPorDia($hoje);
        $saidas = $caixa[0]['saidas'];
        $saldo_final = $caixa[0]['saldo_final'];

        $vlr_saida = $saidas - $_GET['valor'];
        $vlr_saldo_final = $saldo_final + $_GET['valor'];

        $saida = (new Sql('caixa'))->update('data_abertura LIKE "%'.$hoje.'%"',[           
            'saidas'=>$vlr_saida,
            'saldo_final'=>$vlr_saldo_final
        ]);
    }

    public static function excluirEntrada(){
        $hoje = date('Y-m-d');

        $caixa_entradas = (new Sql('caixa_entradas'))->delete('data LIKE "%'.$hoje.'%" AND descricao like "%'.$_GET['descricao'].'%" AND valor = '.$_GET['valor']);
        
        // Ajustar o saldo em caixa
        $caixa = Caixa::buscaPorDia($hoje);
        $entradas = $caixa[0]['entradas'];
        $saldo_final = $caixa[0]['saldo_final'];

        $vlr_entrada = $entradas - $_GET['valor'];
        $vlr_saldo_final = $saldo_final - $_GET['valor'];

        $saida = (new Sql('caixa'))->update('data_abertura LIKE "%'.$hoje.'%"',[           
            'entradas'=>$vlr_entrada,
            'saldo_final'=>$vlr_saldo_final
        ]);
    }

}