<?php

namespace App;

use App\DB\Sql;
use \PDO;

class Clientes {

    public $id;
    public $nome;
    public $telefone;
    public $email;
    public $status;



    public static function getClientes($where = null, $order = null, $limit = null){
       return myArray((new Sql('clientes'))->select('id > 20', 'nome')->fetchAll(PDO::FETCH_CLASS,self::class));

    }

    public static function getByNome($nome) {
        return myArray((new Sql('clientes'))->select('nome LIKE "%'.$nome.'%"', 'nome')->fetchAll(PDO::FETCH_CLASS,self::class));
    }

    public static function getByID($id){
        return (new Sql('clientes'))->select('id = '.$id)->fetchObject(self::class);
    }

    public static function updateStatus($clienteID, $status){
        return (new Sql('clientes'))->update('id = '.$clienteID,[
            'status'=>$status
        ]);
    }

    public static function create($clienteNome, $telefone, $email, $status){
       
        //INSERIR A VAGA NO BANCO
        $obDatabase = new Sql('clientes');
        $sql = $obDatabase->insert([
            'nome'=>$clienteNome,
            'email'=>$telefone,
            'telefone'=>$email,
            'status'=>$status
        ]);

        //RETORNAR MENSAGEM DE SUCESSO
        return $sql;
    }

}