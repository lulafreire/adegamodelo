<?php

namespace App;

use App\DB\Sql;
use \PDO;
use App\Clientes;

class Mesas_ {

    public $id;
    public $cliente;
    public $valor;
    public $status;

    public static function getMesas($where = null, $order = null, $limit = null){
        $sql = myArray((new Sql('mesas'))->select($where,$order,$limit)->fetchAll(PDO::FETCH_CLASS,self::class));

        foreach($sql as $row) {
           
           //Verifia o ID do cliente de cada mesa
           $mesa = $row['id'];
           $clienteID = $row['cliente'];

           if($clienteID !=0)
           {
            $sqlCliente = myArray(Clientes::getByID($clienteID));
            $nomeCliente = $sqlCliente['nome'];
           } else {
            $nomeCliente = "LIVRE";
           }

           $mesas[] = [

                "id"=>$row['id'],
                "cliente"=>$nomeCliente,
                "valor"=>$row['valor'],
                "status"=>$row['status']
           ];
            
        }

        //echo "<pre>"; print_r($mesas); echo "</pre>"; exit;
        return $mesas;


        
    }

    public static function getByID($id){

        $sql = myArray((new Sql('mesas'))->select('id = '.$id)->fetchAll(PDO::FETCH_CLASS,self::class));

        foreach($sql as $row) {
           
           //Verifia o ID do cliente de cada mesa
           $mesa = $row['id'];
           $clienteID = $row['cliente'];

           if($clienteID !=0)
           {
            $sqlCliente = myArray(Clientes::getByID($clienteID));
            $nomeCliente = $sqlCliente['nome'];
           } else {
            $nomeCliente = "LIVRE";
           }

           $mesas[] = [

                "id"=>$row['id'],
                "cliente"=>$nomeCliente,
                "valor"=>$row['valor'],
                "status"=>$row['status']
           ];
            
        }

        //echo "<pre>"; print_r($mesas); echo "</pre>"; exit;
        return $mesas;

    }

}