<?php
class Database {
    private static $instance = null;
    private $pdo, $query, $result, $count, $error = false;

    private function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=" . Config::get('mysql.host') . "; dbname=" . Config::get('mysql.db'),
                Config::get('mysql.user'),
                Config::get('mysql.pass'));
        } catch(PDOException $e) {
            die($e->getMessage()."!!!");
        }
    }

    public static function getInstance() {
        if(!isset(self::$instance)) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function query($sql, $params = []) {
        $this->error = false;
        $this->query = $this->pdo->prepare($sql);
        $j = 1;
        foreach($params as $par) {
            $this->query->bindValue($j, $par);
            $j++;
        }

        if(!$this->query->execute()) {
            $this->error = true;
        } else {
            $this->result = $this->query->fetchAll(PDO::FETCH_OBJ);
            $this->count = $this->query->rowCount();
        }
        
        return $this;
    }

    public function action($action, $table, $where = []) {
        if(count($where) == 3) {
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];
            $operators = ['=', '!=', '>', '<', '>=', '<='];

            if(in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                $this->query($sql, [$value]);
                /*if(!$this->query($sql, [$value])->error) {
                    return $this;
                }*/
            }
            else $this->error = true;
        }
        else $this->error = true;

        return $this;
    }

    public function get($table, $where = []) {
        return $this->action("SELECT *", $table, $where);
    }

    public function getAll($table) {
        return $this->query("SELECT * FROM {$table}");
    }

    public function delete($table, $where = []) {
        return $this->action("DELETE", $table, $where);
    }

    public function insert($table, $fields = []) {
        $values = "";
        foreach($fields as $field) {
            $values .= "?,";
        }
        $values = rtrim($values, ',');
        $sql = "INSERT INTO {$table} (". '`' . implode('`,`', array_keys($fields)) . '`' . ") VALUES ({$values})";
        if(!$this->query($sql, $fields)->getError()) {
            return true;
        }

        return false;
    }

    public function update($table, $id, $fields = []) {
        $set = "";
        foreach($fields as $key=>$field) {
            $set .= "{$key}=?,";
        }
        $set = rtrim($set, ',');
        $fields["id"] = $id;
        $sql = "UPDATE {$table} SET {$set} WHERE id = ?";
        if(!$this->query($sql, $fields)->getError()) {
            return true;
        }

        return false;
    }

    public function first() {
        return $this->getResult()[0];
    }

    public function getError() {
        return $this->error;
    }

    public function getResult() {
        return $this->result;
    }

    public function count() {
        return $this->count;
    }

}
?>