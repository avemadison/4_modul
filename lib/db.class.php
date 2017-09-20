<?php
# для соединения с БД
class DB{
    
    protected $connection;
    /**
     * DB constructor /
     * @param $host
     * @param $user
     * @param $password
     * @param $db_name
     * @throws Exception
     */
    public function __construct($host, $user, $password, $db_name)
    {
        $this->connection = new mysqli($host, $user, $password, $db_name);
        
        if (mysqli_connect_error($this->connection)) {
            throw new Exception('Could not connect to DB');
        }
        
        # для русских символов
        $this->connection->query("SET NAMES 'utf8'");
        $this->connection->query("SET CHARACTER SET 'utf8'");
        $this->connection->query("SET SESSION collation_connection = 'utf8_general_ci'");
    }

    /**
     * @param $sql
     * @return array|bool|mysqli_result data
     * @throws Exception
     */
    public function query($sql) 
    {
        if(!$this->connection) {
            return false;
        }
        
        $result = $this->connection->query($sql);
        
        if(mysqli_error($this->connection)) {
            throw new Exception(mysqli_error($this->connection));
        }
        
        if(is_bool($result)) {
            return $result;
        }
        
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * для избежания sql-инъекций
     * вытесняет специальные символы в str
     * @param $str
     * @return string
     */
    public function escape($str) 
    {
        return mysqli_real_escape_string($this->connection , $str);
    }
}