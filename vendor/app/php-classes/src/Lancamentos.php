<?php

namespace App;

use App\DB\Sql;
use PDO;
use App\Mesas;
use JetBrains\PhpStorm\Language;

class Lancamentos{

    public $id;
    public $num_pedido;
    public $data_hora;
    public $produto;
    public $preco_unit;
    public $quantidade;

    public static function create($mesa, $pedido, $produto, $clienteID){

        // Define a data
        $date = date('Y-m-d H:i:s');

        // Preco unitário do produto
        $v = myarray((new Sql('produtos'))->select('id = '.$produto)->fetchAll(PDO::FETCH_CLASS,self::class));                      
        $preco_unit = $v[0]['preco_venda'];

       //echo "<pre>"; print_r($preco_unit); echo "</pre>"; exit;

        // Verifica se o lançamento se refere a um pedido já existente
        if($pedido !=0){

            // Insere os dados do novo lançamento
            $sql = (new Sql('lancamentos'))->insert([
                'num_pedido'=>$pedido,
                'produto'=>$produto,
                'preco_unit'=>$preco_unit,
                'data_hora'=>$date,
                'quantidade'=>'1',
            ]);

            // atualiza a data fim do pedido
            $update =  (new Sql('pedidos'))->update('num_pedido = '.$pedido,[
                'data_fim'=>$date
            ]);

            // Atualiza subtotal do pedido
            $pedidoData = myArray(Mesas::getByID($mesa));            
            $subtotal = $pedidoData[0]['subtotalPedido'];
            $valor_total = $pedidoData[0]['valor_total'];
            $updateSubtotal =  (new Sql('pedidos'))->update('num_pedido = '.$pedido,[
                'subtotal'=>$subtotal,
                'pgto_total'=>$valor_total
            ]);

            //Atualiza o lucro do pedido
            $lancamentos = Lancamentos::getByPedido($pedido);

            // Calcular Lucro do Pedido
            $lucro = 0;
            foreach ($lancamentos as $key => $value) {		
                $lucro = $lucro + $value['subtotalLucro'];		
            }
            
            $updateLucro =  (new Sql('pedidos'))->update('num_pedido = '.$pedido,[
                'lucro'=>$lucro
            ]);

        } else {

            // Define um número para o pedido, baseado na data e hora
            $num_pedido = date('YmdHis');

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
                'preco_unit'=>$preco_unit,
                'data_hora'=>$date,
                'quantidade'=>'1',
            ]);

            // Grava o subtotal do pedido
            $pedidoData = myArray(Mesas::getByID($mesa));            
            $subtotal = $pedidoData[0]['subtotalPedido'];
            $valor_total = $pedidoData[0]['valor_total'];
            $updateSubtotal =  (new Sql('pedidos'))->update('num_pedido = '.$num_pedido,[
                'subtotal'=>$subtotal,
                'pgto_total'=>$valor_total
            ]);

             //Atualiza o lucro do pedido
             $lancamentos = Lancamentos::getByPedido($num_pedido);

             //echo "<pre>"; print_r($lancamentos); echo "</pre>"; exit;

             // Calcular Lucro do Pedido
             $lucro = 0;
             foreach ($lancamentos as $key => $value) {		
                 $lucro = $lucro + $value['subtotalLucro'];		
             }
             
             $updateLucro =  (new Sql('pedidos'))->update('num_pedido = '.$num_pedido,[
                 'lucro'=>$lucro
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
        $date = date('Y-m-d H:i:s');

        // Preco unitário do produto
        $v = myarray((new Sql('produtos'))->select('id = '.$produto)->fetchAll(PDO::FETCH_CLASS,self::class));                      
        $preco_unit = $v[0]['preco_venda'];

        // Verifica se a quantidade do produto é igual a ZERO
        $produtoQtd = (new Sql('lancamentos'))->select('produto = '.$produto.' AND num_pedido = '.$pedido, null, null, null, 'SUM(quantidade) AS total ')->fetchColumn();

        //echo "<pre>"; print_r($produtoQtd); echo "</pre>"; exit;

        if($produtoQtd >= 1){

            // Insere os dados do novo lançamento
            $sql = (new Sql('lancamentos'))->insert([
                'num_pedido'=>$pedido,
                'produto'=>$produto,
                'preco_unit'=>$preco_unit,
                'data_hora'=>$date,
                'quantidade'=>'-1',
            ]);

            // atualiza a data fim do pedido
            $update =  (new Sql('pedidos'))->update('num_pedido = '.$pedido,[
                'data_fim'=>$date
            ]);

            // Atualiza subtotal do pedido
            $pedidoData = myArray(Mesas::getByID($mesa));            
            $subtotal = $pedidoData[0]['subtotalPedido'];
            $valor_total = $pedidoData[0]['valor_total'];
            $updateSubtotal =  (new Sql('pedidos'))->update('num_pedido = '.$pedido,[
                'subtotal'=>$subtotal,
                'pgto_total'=>$valor_total
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
        $lucro = 0;
        foreach ($produtos as $key=>$value) {
            
            $produtoID = $value['produto'];
            $preco_unit = $value['preco_unit'];

            // Soma a quantidade de cada produto localizado
            $produtoQtd = (new Sql('lancamentos'))->select('produto = '.$produtoID.' AND num_pedido = '.$data, null, null, null, 'SUM(quantidade) AS total ')->fetchColumn();
            
            // Pesquisa os dados dos produtos existentes no pedido
            $produtosData = myArray((new Sql('produtos'))->select('id = '.$produtoID)->fetchAll(PDO::FETCH_CLASS,self::class));
           
            $produtoNome = $produtosData[0]['nome'];
            $produtoValor = $produtosData[0]['preco_venda'];
            $precoCusto = $produtosData[0]['preco_custo'];
            $custoVenda = $produtoQtd * $precoCusto;
            $subtotalProduto = $produtoQtd * $preco_unit;
            $t = $t + $subtotalProduto;
            $subtotalLucro = $subtotalProduto - $custoVenda;
            $lucro = $lucro + $subtotalLucro;

            $pedidoData[] = 
                [                    
                    "produto"=>$produtoNome,
                    "produtoID"=>$produtoID,
                    "quantidade"=>$produtoQtd,
                    "valor_unit"=>$preco_unit,
                    "preco_custo"=>$precoCusto,
                    "custo_venda"=>$custoVenda,
                    "subtotalProduto"=>$subtotalProduto,
                    "subtotalLucro"=>$subtotalLucro,
                    "lucro"=>$lucro
                ];

        }    return $pedidoData;    

    }

    public static function getByProduto($produto){
        return myarray((new Sql('lancamentos'))->select('produto = '.$produto, 'num_pedido', 'data_hora DESC', null)->fetchAll(PDO::FETCH_CLASS,self::class));                      
    }
    

    public static function sumByProduto($produto) {

        // Pesquisa todos os lançamentos do produto selecionado
        $p = myarray((new Sql('lancamentos'))->select('produto = '.$produto)->fetchAll(PDO::FETCH_CLASS,self::class));                      
        $i = 0;
		foreach ($p as $key => $value) {
			$quantidade = $value['quantidade'];
			$preco_unit = $value['preco_unit'];
			$valor_lancamento = $quantidade * $preco_unit;
			$i = $i + $valor_lancamento;
		}

        return $i;        
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

    public static function salvaDesconto($desconto, $pedido, $mesa){
        
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

        // Atualizar valor do desconto na tabela Pedidos
        $updateDescontos =  (new Sql('pedidos'))->update('num_pedido = '.$pedido,[
            'descontos'=>$desconto
        ]);  
        
        // Atualiza subtotal do pedido
        $pedidoData = myArray(Mesas::getByID($mesa));            
        $valor_total = $pedidoData[0]['valor_total'];
        $updateSubtotal =  (new Sql('pedidos'))->update('num_pedido = '.$pedido,[
            'pgto_total'=>$valor_total
        ]);
        

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

    public static function salvaAcrescimo($acrescimo, $pedido, $mesa){
        
        // Verifica se já existe acr´scimo cadastrado para o pedido
        $sqlAcresc = myArray((new Sql('acrescimos'))->select('num_pedido = '.$pedido)->fetchAll(PDO::FETCH_CLASS,self::class));
        $count = count($sqlAcresc);

        if($count==0){
            $sql = (new Sql('acrescimos'))->insert([
                'num_pedido'=>$pedido,
                'valor'=>$acrescimo
            ]);
        } else {
            $updateAcrescimo = (new Sql('acrescimos'))->update('num_pedido = '.$pedido,[                                
                'valor'=>$acrescimo
            ]);
        }

        // Atualizar valor do acrescimo na tabela Pedidos
        $updateAcrescimos =  (new Sql('pedidos'))->update('num_pedido = '.$pedido,[
            'acrescimos'=>$acrescimo
        ]); 
        
        // Atualiza subtotal do pedido
        $pedidoData = myArray(Mesas::getByID($mesa));            
        $valor_total = $pedidoData[0]['valor_total'];
        $updateSubtotal =  (new Sql('pedidos'))->update('num_pedido = '.$pedido,[
            'pgto_total'=>$valor_total
        ]);
        

        return true;
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

    public static function salvaAdiantamento($adiantamento, $pedido){
        
        // Verifica se já existe acr´scimo cadastrado para o pedido
        $sqlAdiant = myArray((new Sql('adiantamentos'))->select('num_pedido = '.$pedido)->fetchAll(PDO::FETCH_CLASS,self::class));
        $count = count($sqlAdiant);

        if($count==0){
            $sql = (new Sql('adiantamentos'))->insert([
                'num_pedido'=>$pedido,
                'valor'=>$adiantamento
            ]);
        } else {
            $updateAdiantamentos = (new Sql('adiantamentos'))->update('num_pedido = '.$pedido,[                                
                'valor'=>$adiantamento
            ]);
        }

        // Atualizar valor do adiantamtentos na tabela Pedidos
        $updateadiAntamtentoss =  (new Sql('pedidos'))->update('num_pedido = '.$pedido,[
            'adiantamentos'=>$adiantamento
        ]);   
        

        return true;
    }

    public static function excluirAdiantamentos($pedido){
        return (new Sql('adiantamentos'))->delete('num_pedido = '.$pedido);
    }

    public static function getTop(){

        // Esvazia a tabela temporária Top5
        $deleta = Top5::delete(null);

        // Pesquisa todos os produtos lançados
        $sql = myarray((new Sql('lancamentos'))->select(null, 'produto', null, null)->fetchAll(PDO::FETCH_CLASS,self::class));
        
        foreach ($sql as $key => $value) {
            
            $produtoID = $value['produto'];

            // Soma a quantidade de cada produto localizado
            $qtd = (new Sql('lancamentos'))->select('produto = '.$produtoID, null, null, null, 'SUM(quantidade) AS total ')->fetchColumn();

            // Pesquisa o nome e o valor unitário de cada produto
            $produtoData = myarray((new Sql('produtos'))->select('id = '.$produtoID)->fetchAll(PDO::FETCH_CLASS,self::class));

            $produtoNome = $produtoData[0]['nome'];
            $produtoValor = $produtoData[0]['preco_venda'];
            $totalVenda = $qtd * $produtoValor;

            // Grava as informações no banco de dados temporário
            $grava = Top5::create($produtoNome, $qtd, $totalVenda);
        }
        
        // Pesquisa orndenando pelo produto mais vendido
        $top = myarray((new Sql('top5'))->select(null, null, 'valor DESC', 5,)->fetchAll(PDO::FETCH_CLASS,self::class));

           foreach ($top as $key => $value) {
           $produto = $value['produto'];
           $quantidade = $value['quantidade'];
           $valor = $value['valor'];

           $top5[] = [
            'produto'=>$produto,
            'quantidade'=>$quantidade,
            'valor'=>$valor
           ];
        } 
               
        return $top5;
    }   


}