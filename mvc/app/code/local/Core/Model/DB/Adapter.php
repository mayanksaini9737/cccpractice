<?php
class Core_Model_DB_Adapter
{
    public $config = [
        "database" => "ccc_practice",
        "server" => "localhost",
        "password" => "",
        "user" => "root",
    ];
    public $connect = null;
    public function connect()
    {
        if (is_null($this->connect)) {
            $this->connect = mysqli_connect(
                $this->config["server"],
                $this->config["user"],
                $this->config["password"],
                $this->config["database"]
            );
        }
        return $this->connect;
    }
    public function execute($query)
    {
        $this->connect();
        $result = mysqli_query($this->connect(), $query);
        if ($result) {
            return $result;
        } else {
            return FALSE;
        }
    }
    public function fetchAll($query)
    {
        $row = [];
        $this->connect();
        $result = $this->execute($query);
        while ($data = mysqli_fetch_assoc($result)) {
            $row[] = $data;
        }
        return $row;
    }
    public function fetchPairs($query)
    {

    }
    public function fetchOne($query)
    {

    }
    public function fetchRow($query)
    {
        $row = [];
        $this->connect();
        $result = $this->execute($query);
        while ($data = mysqli_fetch_assoc($result)) {
            $row = $data;
        }
        return $row;
    }
    public function insert($query)
    {
        $this->connect();
        $result = $this->execute($query);
        if ($result) {
            return mysqli_insert_id($this->connect());
        } else {
            return FALSE;
        }
    }
    public function update($query)
    {
        $this->connect();
        $result = $this->execute($query);
        if ($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function delete($query)
    {
        $this->connect();
        $result = $this->execute($query);
        if ($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function query($query)
    {

    }

}
?>