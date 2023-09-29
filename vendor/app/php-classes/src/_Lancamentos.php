<?php

namespace App;

use App\DB\Sql;
use PDO;
use App\Mesas;

class _Lancamentos{

    public $id;
    public $num_pedido;
    public $data_hora;
    public $produto;
    public $quantidade;

    public static function create($mesa, $pedido, $produto, $clienteID){

        // Define a data
        $date = date('Y-m-d h:i:s');

        // Verifica se o lançamento se refere a um pedido já existente
        if($pedido !=0){

            // Insere os dados do novo lançamento
            $sql = (new Sql('lancamentos'))->insert([
                'num_pedido'=>$pedido,
                'produto'=>$produto,
                'data_hora'=>$date,
                'quantidade'=>'1',
            ]);

            // atualiza a data fim do pedido
            $update =  (new Sql('pedidos'))->update('num_pedido = '.$pedido,[
                'data_fim'=>$date
            ]);

            // Atualiza subtotal do pedido
            $pedidoData = Lancamentos::getByPedido($pedido);
            $subtotal = $pedidoData['subtotal'];

            $updateSubtotal =  (new Sql('pedidos'))->update('num_pedido = '.$pedido,[
                'subtotal'=>$subtotal
            ]);


        } else {

            // Define um número para o pedido, baseado na data e hora
            $num_pedido = date('Ymdhis');

            // Cria o pedido
            $sql = (new Sql('pedidos'))->insert([
                'cliente'=>$clienteID,
                'status'=>'0',
                'data_ini'=>$date,
                'data_fim'=>$date,
                'num_pedido'=>$num_pedido
            ]);

            // Atualiza o status do cliente para 1 = Ocupado
            $cliente = Clientes::updateStatus($clienteID, 1);

            // Grava o lançamento relacionando ao pedido
            $lancamento = (new Sql('lancamentos'))->insert([
                'num_pedido'=>$num_pedido,
                'produto'=>$produto,
                'data_hora'=>$date,
                'quantidade'=>'1',
            ]);

            // Atualiza subtotal do pedido
            $pedidoData = Lancamentos::getByPedido($num_pedido);
            $subtotal = $pedidoData['subtotal'];

            $updateSubtotal =  (new Sql('pedidos'))->update('num_pedido = '.$pedido,[
                'subtotal'=>$subtotal
            ]);

            // Define status 1 = ATIVO para a mesa
            $status = 1;

            // Atualiza a mesa informando o número do pedido e do cliente
            $updateMesa = Mesas::update($mesa, $clienteID, $status, $num_pedido);

        }             

        return true;
    }

    public static function remove($mesa, $pedido, $produto, $clienteID){

        // Define a data
        $date = date('Y-m-d h:i:s');

        // Verifica se a quantidade do produto é igual a ZERO
        $produtoQtd = (new Sql('lancamentos'))->select('produto = '.$produto.' AND num_pedido = '.$pedido, null, null, null, 'SUM(quantidade) AS total ')->fetchColumn();

        //echo "<pre>"; print_r($produtoQtd); echo "</pre>"; exit;

        if($produtoQtd >= 1){

            // Insere os dados do novo lançamento
            $sql = (new Sql('lancamentos'))->insert([
                'num_pedido'=>$pedido,
                'produto'=>$produto,
                'data_hora'=>$date,
                'quantidade'=>'-1',
            ]);

            // atualiza a data fim do pedido
            $update =  (new Sql('pedidos'))->update('num_pedido = '.$pedido,[
                'data_fim'=>$date
            ]);

        } else {

            return false;

        }              

        return true;
    }

    public static function removeAll($pedido, $produto, $mesa, $cliente){

        // Apaga o registro na tabela tb_users
        $remove = (new Sql('lancamentos'))->delete('produto = '.$produto.' AND num_pedido = '.$pedido);

        // Verifica se ainda há lançamentos para o pedido selecionado
        $lancamentos = myArray((new Sql('lancamentos'))->select('num_pedido = '.$pedido)->fetchAll(PDO::FETCH_CLASS,self::class));
        $count = count($lancamentos);
        
        if($count == 0) {
            // Caso não haja mais lancamentos, exlui o pedido
            $excluiPedido = (new Sql('pedidos'))->delete('num_pedido = '.$pedido);

            // Exclui Acréscimos, Descontos e Adiantamentos
            $del_acrescimos = Lancamentos::excluirAcrescimos($pedido);
            $del_descontos = Lancamentos::excluirDesconto($pedido);
            $del_adiantamentos = Lancamentos::excluirAdiantamentos($pedido);

            // Libera a mesa
            $updateMesa = (new Sql('mesas'))->update('id = '.$mesa,[
                'status'=>0,
                'cliente'=>$mesa,
                'pedido'=>0
            ]);

            // Altera o status do cliente
            $updateCliente = (new Sql('clientes'))->update('id = '.$cliente,[                                
                'status'=>0
            ]);

            return true;
        }

    }

    public static function getByPedido($data){
        
        // Agrupa os produtos existentes no pedido
        $produtos = myarray((new Sql('lancamentos'))->select('num_pedido = '.$data, 'produto', null, null)->fetchAll(PDO::FETCH_CLASS,self::class));                      
               
        $t = 0;
        foreach ($produtos as $key=>$value) {
            
            $produtoID = $value['produto'];

            // Soma a quantidade de cada produto localizado
            $produtoQtd = (new Sql('lancamentos'))->select('produto = '.$produtoID.' AND num_pedido = '.$data, null, null, null, 'SUM(quantidade) AS total ')->fetchColumn();

            
            // Pesquisa os dados dos produtos existentes no pedido
            $produtosData = myArray((new Sql('produtos'))->select('id = '.$produtoID)->fetchAll(PDO::FETCH_CLASS,self::class));
           
            $produtoNome = $produtosData[0]['nome'];
            $produtoValor = $produtosData[0]['preco_venda'];
            $subtotalProduto = $produtoQtd * $produtoValor;
            $t = $t + $subtotalProduto;

            $pedidoData[] = 
                [                    
                    "produto"=>$produtoNome,
                    "produtoID"=>$produtoID,
                    "quantidade"=>$produtoQtd,
                    "valor_unit"=>$produtoValor,
                    "subtotalProduto"=>$subtotalProduto,
                ];

        }    return $pedidoData;    

    }
    
    public static function getDescontos($data){
                   
        // Pesquisa se há descontos cadastrados para o pedido
        $descontos = myArray((new Sql('descontos'))->select('num_pedido = '.$data)->fetchAll(PDO::FETCH_CLASS,self::class));
        $countDescontos = count($descontos);     
        
        if($countDescontos!=0) {
            $valor_desconto = $descontos[0]['valor'];
        } else {
            $valor_desconto = 0;
        }

        return $valor_desconto;

    }

    public static function salvaDesconto($desconto, $pedido){
        
        // Verifica se já existe desconto cadastrado para o pedido
        $sqlDesc = myArray((new Sql('descontos'))->select('num_pedido = '.$pedido)->fetchAll(PDO::FETCH_CLASS,self::class));
        $count = count($sqlDesc);

        if($count==0){
            $sql = (new Sql('descontos'))->insert([
                'num_pedido'=>$pedido,
                'valor'=>$desconto
            ]);
        } else {
            $updateDesconto = (new Sql('descontos'))->update('num_pedido = '.$pedido,[                                
                'valor'=>$desconto
            ]);
        }
        
        

        return true;
    }

    public static function excluirDesconto($pedido){
        return (new Sql('descontos'))->delete('num_pedido = '.$pedido);
    }

    public static function getAcrescimos($data){

        // Pesquisa se há acréscimos cadastrados para o pedido
        $acrescimos = myArray((new Sql('acrescimos'))->select('num_pedido = '.$data)->fetchAll(PDO::FETCH_CLASS,self::class));
        $countacrescimos = count($acrescimos);     
        
        if($countacrescimos!=0) {
            $valor_acrescimo = $acrescimos[0]['valor'];
        } else {
            $valor_acrescimo = 0;
        }

        return $valor_acrescimo;

    }

    public static function excluirAcrescimos($pedido){
        return (new Sql('acrescimos'))->delete('num_pedido = '.$pedido);
    }

    public static function getAdiantamentos($data){

        // Pesquisa se há acréscimos cadastrados para o pedido
        $adiantamentos = myArray((new Sql('adiantamentos'))->select('num_pedido = '.$data)->fetchAll(PDO::FETCH_CLASS,self::class));
        $countadiantamentos = count($adiantamentos);     
        
        if($countadiantamentos!=0) {
            $valor_adiantamento = $adiantamentos[0]['valor'];
        } else {
            $valor_adiantamento = 0;
        }

        return $valor_adiantamento;

    }

    public static function excluirAdiantamentos($pedido){
        return (new Sql('adiantamentos'))->delete('num_pedido = '.$pedido);
    }


}