<?php

namespace App\models;

use Aura\SqlQuery\QueryFactory;
use PDO;

class Database
{
    private $pdo;
    private $queryFactory;
    
    public function __construct(PDO $pdo, QueryFactory $queryFactory)
    {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
    }
 
    public function get($table, $id_col, $id_value)
    {
        $select = $this->queryFactory->newSelect();
        $select
            ->cols(['*'])
            ->from($table)
            ->where("$id_col = :id")
            ->bindValue('id', $id_value);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $result = $sth->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    public function getAll($table)
    {
        $select = $this->queryFactory->newSelect();
        $select
            ->cols(['*'])
            ->from($table);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $result = $sth->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    public function delete($table, $id_col, $id_value)
    {
        $delete = $this->queryFactory->newDelete();
        $delete
            ->from($table)
            ->where("$id_col = :id")
            ->bindValue('id', $id_value);
        
        $sth = $this->pdo->prepare($delete->getStatement());
        $sth->execute($delete->getBindValues());
    }

    public function insert($table, $data)
    {
        $insert = $this->queryFactory->newInsert();
        $insert
            ->into($table)
            ->cols($data);

        $sth = $this->pdo->prepare($insert->getStatement());
        $sth->execute($insert->getBindValues());
        $name = $insert->getLastInsertIdName('id');

        return $this->pdo->lastInsertId($name);
    }

    public function update($table, $data, $id_col, $id_value) 
    {
        $update = $this->queryFactory->newUpdate();
        $update
            ->table($table)
            ->cols($data)
            ->where("$id_col = :id")
            ->bindValue('id', $id_value);

        $sth = $this->pdo->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
    }
}
?>