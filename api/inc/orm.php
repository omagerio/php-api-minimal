<?php
class DbOrm
{
    public $id;
    public $conn;

    public function __construct($host, $username, $password, $database)
    {
        $this->id = md5(microtime(true));
        $this->conn = new mysqli($host, $username, $password, $database);
        $this->conn->set_charset("utf8");
        $this->conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
    }

    protected function assocToObj($assocArray)
    {
        $objArray = array();
        foreach ($assocArray as $assocItem) {
            $objArray[] = (object)$assocItem;
        }
        return $objArray;
    }

    protected function getWhereString($where, $whereParams)
    {
        foreach ($whereParams as $param) {
            $pos = strpos($where, "?");
            if ($pos !== false) {
                $where = substr_replace($where, '"' . $this->conn->escape_string($param) . '"', $pos, 1);
            }
        }
        return $where;
    }

    protected function getValuesString($item)
    {
        $columns = array_keys(get_object_vars($item));
        $values = [];
        foreach ($columns as $column) {
            $value = $item->{$column};
            $values[] = $item->{$column} === null ? "NULL" : '"' . $this->conn->real_escape_string($item->{$column}) . '"';
        }

        return implode(",", $values);
    }

    protected function getSetString($item)
    {
        $columns = array_keys(get_object_vars($item));
        $set = [];
        foreach ($columns as $column) {
            $set[] = $column . " = " . ($item->{$column} === null ? "NULL" : '"' . $this->conn->escape_string($item->{$column}) . '"');
        }
        return implode(",", $set);
    }

    public function put($table, &$item)
    {
        $is_insert = true;
        if(isset($item->id)){
            $db_item = $this->read($table, "id=?", [$item->id]);
            $is_insert = $db_item == null;
        }

        if ($is_insert) {
            $columns = array_keys(get_object_vars($item));
            $preparedParams = array();
            foreach ($columns as $column) {
                $preparedParams[] = "?";
            }

            $query = "INSERT INTO " . $table . " (" . implode(",", $columns) . ") VALUES (" . implode(",", $preparedParams) . ")";

            $stmt = $this->conn->prepare($query);
            if ($this->conn->error != null) {
                throw new Exception($this->conn->error);
            }
            $stmt = $this->bindParams($stmt, array_values(get_object_vars($item)));
        } else {
            $where = " id = ? ";
            $whereParams  = [$item->id];
            $query = "UPDATE " . $table . " SET " . $this->getSetString($item) . " WHERE " . $where;
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                throw new Exception($this->conn->error);
            }
            $stmt = $this->bindParams($stmt, $whereParams);
        }

        $stmt->execute();
        $insert_id = $this->conn->insert_id;
        if($insert_id != null){
            $item->id = $insert_id;
        }

        $error = $stmt->error;
        if($error != null){
            throw new Exception($error);
        }
    }

    public function delete($table, $where = null, $whereParams = array())
    {
        $query = "DELETE FROM " . $table . ($where != null ? " WHERE " . $where : "");
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            throw new Exception($this->conn->error);
        }
        $stmt = $this->bindParams($stmt, $whereParams);
        $stmt->execute();
    }

    public function bindParams($stmt, $whereParams)
    {
        if (count($whereParams) > 0) {
            $types = str_pad("", count($whereParams), "s");
            $funcParams = array();
            $funcParams[] = $types;
            for ($i = 0; $i < count($whereParams); $i++) {
                $funcParams[] = &$whereParams[$i];
            }
            call_user_func_array(array($stmt, "bind_param"), $funcParams);
        }
        return $stmt;
    }

    public function list($table, $where = null, $whereParams = array(), $page = null, $items_per_page = null)
    {
        if ($where === null) {
            $query = "SELECT * FROM " . $table;
        } else {
            $query = "SELECT * FROM " . $table . " WHERE " . $where;
        }

        if ($page !== null && $items_per_page !== null) {
            $query .= " limit " . ($page * $items_per_page) . "," . $items_per_page;
        }

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            throw new Exception($this->conn->error);
        }
        $stmt = $this->bindParams($stmt, $whereParams);

        $stmt->execute();
        $results = $stmt->get_result();

        return $this->assocToObj($results->fetch_all(MYSQLI_ASSOC));
    }

    public function read($table, $where = null, $whereParams = array())
    {
        $items = $this->list($table, $where, $whereParams);
        if ($items) {
            return (object)$items[0];
        }
        return null;
    }

    public function query($query, $type = MYSQLI_ASSOC)
    {
        $result = $this->conn->query($query)->fetch_all($type);
        return $result;
    }
}
