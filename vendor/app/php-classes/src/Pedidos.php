<?php

namespace App;

use App\DB\Sql;
use \PDO;
use App\Produtos;

class Pedidos{

    public $id;
    public $num_pedido;
    public $cliente;
    public $status;
    public $data_ini;
    public $data_fim;
    public $subtotal;
    public $descontos;
    public $acrescimos;
    public $adiantamentos;
    public $pgto_dinheiro;
    public $pgto_pix;
    public $pgto_debito;
    public $pgto_credito;
    public $pgto_total;
    

    public static function create(){
        // Cadastrar o pedido e gravar o lançamento inicial
    }

    public static function registrarCliente($pedidoID, $clienteID, $mesaID){
        return (new Sql('pedidos'))->update('num_pedido = '.$pedidoID,[
            'cliente'=>$clienteID
        ]);
    }

    public static function lancaPgto($pgto_dinheiro, $pgto_pix, $pgto_debito, $pgto_credito, $pedido){
        return (new Sql('pedidos'))->update('num_pedido = '.$pedido,[
            'pgto_dinheiro'=>$pgto_dinheiro,
            'pgto_pix'=>$pgto_pix,
            'pgto_debito'=>$pgto_debito,
            'pgto_credito'=>$pgto_credito
        ]);
    }

    public static function encerraPedido($pedido, $mesa, $cliente){

        // Define a data
        $date = date('Y-m-d H:i:s');        

        $encerraPedido = (new Sql('pedidos'))->update('num_pedido = '.$pedido,[
            'status'=>1,
            'data_fim'=>$date
        ]);

        $encerraMesa = (new Sql('mesas'))->update('id = '.$mesa,[
            'status'=>0,
            'cliente'=>$mesa,
            'pedido'=>0
        ]);

        $liberaCliente = (new Sql('clientes'))->update('id = '.$cliente,[
            'status'=>0,
        ]);

        return true;

    }

    public static function update(){
        // atualiza o pedido a partir de um novo lançamento
    }

    public static function getSum($where){

        $sql = myarray((new Sql('pedidos'))->select($where)->fetchAll(PDO::FETCH_CLASS,self::class));
        //echo "<pre>"; print_r($sql); echo "</pre>"; exit;
        
        $soma = myArray((new Sql('pedidos'))->select($where, null, null, null, 'SUM(pgto_total) AS total ')->fetchColumn());
        if($soma==''){
            return 0;
        } else {
            return $soma;
        }

    }

    public static function getSumPgto($forma_pgto){        
        
        $soma = myArray((new Sql('pedidos'))->select(null, null, null, null, 'SUM('.$forma_pgto.') AS total ')->fetchColumn());
        if($soma==''){
            return 0;
        } else {
            return $soma;
        }

    }

    public static function getSumLucro($where){
        $soma = myArray((new Sql('pedidos'))->select($where, null, null, null, 'SUM(lucro) AS lucro_total ')->fetchColumn());
        if($soma==''){
            return 0;
        } else {
            return round($soma, 2);
        }
    }

    public static function delete($pedido, $mesa, $cliente){
    
        //Deleta o pedido
        $del_pedido = (new Sql('pedidos'))->delete('num_pedido = '.$pedido);

        // Exclui todos os lancamentos
        $del_lancamentos = (new Sql('lancamentos'))->delete('num_pedido = '.$pedido);

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

    public static function getAll($where, $group, $order, $limit){
        $sql = myarray((new Sql('pedidos'))->select($where, $group, $order, $limit)->fetchAll(PDO::FETCH_CLASS,self::class));
        $count = count($sql);

        if($count!=0){

            foreach ($sql as $key => $value) {
                // identifica o cliente pelo ID
                $clienteID = $value['cliente'];
     
                // pesquisa o nome do cliente
                $sqlCliente = myArray(Clientes::getByID($clienteID));
                $nomeCliente = $sqlCliente['nome'];
     
                $pedidos[] = [
                     "nome_cliente"=>$nomeCliente,
                     "clienteID"=>$clienteID,
                     "num_pedido"=>$value['num_pedido'],
                     "data_ini"=>$value['data_ini'],
                     "data_fim"=>$value['data_fim'],
                     "subtotal"=>$value['subtotal'],
                     "pgto_total"=>$value['pgto_total']
                ];
             }                                 

        } else {
            
            $pedidos = 0;
            /*return $pedidos[] = [
                "nome_cliente"=>'',
                "clienteID"=>'',
                "num_pedido"=>'',
                "data_ini"=>'',
                "data_fim"=>'',
                "subtotal"=>'',
                "pgto_total"=>''
           ];
           */
        }

        return $pedidos;       
        
    }

    public static function getByData($data){
        $sql = myarray((new Sql('pedidos'))->select('cliente = '.$data. ' AND status = 0')->fetchAll(PDO::FETCH_CLASS,self::class));        
        return $sql;
    }

    public static function getByCliente($id){
        $sql = myarray((new Sql('pedidos'))->select('cliente = '.$id. ' AND status = 0')->fetchAll(PDO::FETCH_CLASS,self::class));        
        return $sql;
    }

    public static function getByID($id){
        
        return myArray((new Sql('pedidos'))->select('num_pedido = '.$id)->fetchAll(PDO::FETCH_CLASS,self::class));
                
    }

}