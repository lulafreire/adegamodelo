<?php

namespace App;

use \App\DB\Sql;
use PDO;
use \App\Clientes;
use \App\Pedidos;
use \App\Lancamentos;

class Mesas{

    public $id;
    public $cliente;
    public $status;
    public $pedido;

    public static function update($id, $cliente, $status, $pedido){

        $update =  (new Sql('mesas'))->update('id = '.$id,[
            'cliente'=>$cliente,
            'status'=>$status,
            'pedido'=>$pedido
        ]);
        
    }

    public static function registrarCliente($pedidoID, $clienteID, $mesaID){
        return (new Sql('mesas'))->update('id = '.$mesaID,[
            'cliente'=>$clienteID
        ]);
    }

    public static function getAll(){

       // Pesquisa todas as mesas
       $sql = myArray((new Sql('mesas'))->select()->fetchAll(PDO::FETCH_CLASS,self::class));
       
       foreach ($sql as $key => $value) {
           
            // identifica o cliente pelo ID
            $clienteID = $value['cliente']; 
                        
            // Número da Mesa
            $mesaNumero = $value['id'];            

            // Status
            $statusMesa = $value['status'];            

            
            if($clienteID < 21 & $statusMesa ==0){
                $nomeCliente = "LIVRE";
            } else {
                // pesquisa o nome do cliente
                $sqlCliente = myArray(Clientes::getByID($clienteID));
                $nomeCliente = $sqlCliente['nome'];
            }                       

            // Pesquisa se há pedido aberto em nome do cliente
            $sqlPedido = myArray(Pedidos::getByCliente($clienteID));
            $countPedidos = count($sqlPedido);
                if($countPedidos!=0){
                    $num_pedido = $sqlPedido[0]['num_pedido'];

                    // Pesquisa o valor total do pedido
                    $lancamentos = myArray(Lancamentos::getByPedido($num_pedido));
                    //$valor_total = $lancamentos['valor_total'];
                    $s = 0;
                    foreach ($lancamentos as $key => $value) {
                        $subtotalProduto = $value['subtotalProduto'];
                        $s = $s + $subtotalProduto;
                    }
                    
                    // Valores
                    $subtotalPedido = $s;
                    $desconto = Lancamentos::getDescontos($num_pedido);
                    $acrescimo = Lancamentos::getAcrescimos($num_pedido);
                    $adiantamento = Lancamentos::getAdiantamentos($num_pedido);
                    $valor_total = $subtotalPedido + $acrescimo - $desconto - $adiantamento; 
                } else {
                    $num_pedido = 0;
                    $valor_total = 0;
                    $subtotalPedido = 0;
                }                        
            
            $mesas[] = 
                [                    
                    "id"=>$mesaNumero,
                    "clienteID"=>$clienteID,
                    "cliente"=>$nomeCliente,
                    "status"=>$statusMesa,
                    "num_pedido"=>$num_pedido,
                    "valor_total"=>$valor_total,
                    "subtotalPedido"=>$subtotalPedido
                ];
            
       }
       
       return $mesas;
    }

    public static function getByID($id){
        $sql = myArray((new Sql('mesas'))->select('id = '.$id)->fetchAll(PDO::FETCH_CLASS,self::class));
       foreach ($sql as $key => $value) {
           
            // identifica o cliente pelo ID
            $clienteID = $value['cliente']; 
                        
            // Número da Mesa
            $mesaNumero = $value['id'];            

            // Status
            $statusMesa = $value['status'];            

            
            if($clienteID < 21 & $statusMesa ==0){
                $nomeCliente = "LIVRE";
            } else {
                // pesquisa o nome do cliente
                $sqlCliente = myArray(Clientes::getByID($clienteID));
                $nomeCliente = $sqlCliente['nome'];
            }                       

            // Pesquisa se há pedido aberto em nome do cliente
            $sqlPedido = myArray(Pedidos::getByCliente($clienteID));
            $countPedidos = count($sqlPedido);
                if($countPedidos!=0){
                    $num_pedido = $sqlPedido[0]['num_pedido'];

                    // Pesquisa o valor total do pedido
                    $lancamentos = myArray(Lancamentos::getByPedido($num_pedido));
                    //$valor_total = $lancamentos['valor_total'];
                    $s = 0;
                    foreach ($lancamentos as $key => $value) {
                        $subtotalProduto = $value['subtotalProduto'];
                        $s = $s + $subtotalProduto;
                    }
                    
                    // Valores
                    $subtotalPedido = $s;
                    $desconto = Lancamentos::getDescontos($num_pedido);
                    $acrescimo = Lancamentos::getAcrescimos($num_pedido);
                    $adiantamento = Lancamentos::getAdiantamentos($num_pedido);
                    $valor_total = $subtotalPedido + $acrescimo - $desconto - $adiantamento; 
                } else {
                    $num_pedido = 0;
                    $subtotalPedido = 0;
                    $valor_total = 0;
                }                        
            
            $mesas[] = 
                [                    
                    "id"=>$mesaNumero,
                    "clienteID"=>$clienteID,
                    "cliente"=>$nomeCliente,
                    "status"=>$statusMesa,
                    "num_pedido"=>$num_pedido,
                    "subtotalPedido"=>$subtotalPedido,
                    "valor_total"=>$valor_total
                ];
            
       }
       
       return $mesas;
    }

}