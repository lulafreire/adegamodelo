<?php

namespace App\Db;

use \PDO;
use PDOException;

class Sql{

    const HOST = 'localhost';
    const NAME = 'adegamodelo';
    const USER = 'root';
    const PASS = '';

    private $table;
    private $connection;

    public function __construct($table = null)
    {
        $this->table = $table;
        $this->setConnection();
    }

    private function setConnection(){
        try {
           $this->connection = new PDO('mysql:host='.self::HOST.';dbname='.self::NAME,self::USER,self::PASS);
           $this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('ERROR: '.$e->getMessage());
        }
    }

    public function execute($query,$params = []){
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute($params);
            return $statement;
        } catch (PDOException $e) {
            die('ERROR: '.$e->getMessage());
        }

    }

    public function insert($values){

        //DADOS DA QUERY
        $fields = array_keys($values);
        $binds  = array_pad([],count($fields),'?');

        //MONTA A QUERY
        $query = 'INSERT INTO '.$this->table.' ('.implode(',',$fields).') VALUES ('.implode(',',$binds).')';
        
        // EXECUTA O INSERT
        $this->execute($query,array_values($values));

        return $this->connection->lastInsertId();

    }

    public function select($where = null, $group = null, $order = null, $limit = null, $fields = '*'){

        
        
        //DADOS DA QUERY
        $where = strlen($where) ? 'WHERE '.$where : '';
        $group = strlen($group) ? 'GROUP BY '.$group : '';
        $order = strlen($order) ? 'ORDER BY '.$order : '';
        $limit = strlen($limit) ? 'LIMIT '.$limit : '';

        // MONTA A QUERY
        $query = 'SELECT '.$fields.' FROM '.$this->table.' '.$where.' '.$group.' '.$order.' '.$limit;
       
        //echo "<pre>"; print_r($query); echo "</pre>";

        // EXECUTA A QUERY
        return $this->execute($query);
    }

    public function update($where,$values){
        // DADOS DA QUERY
        $fields = array_keys($values);

        // MONTA A QUERY
        $query = 'UPDATE '.$this->table.' SET '.implode('=?,',$fields).'=? WHERE '.$where;
        
        // EXECUTA A QUERY
        $this->execute($query,array_values($values));
        
        //RETURNA SUCESSO
        return true;
    }

    public function delete($where){
        //MONTA A QUERY
        $query = 'DELETE FROM '.$this->table. ' WHERE '.$where;

        //EXECUTA A QUERY
        $this->execute($query);

        //RETURNA SUCESSO
        return true;
    }
}