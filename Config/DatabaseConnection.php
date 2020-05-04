<?php

class DatabaseConnection
{

    private $host;
    private $port;
    private $username;
    private $password;
    private $sid;

    private $conn;

    private $configPathFile = 'database_config.ini';

    public function __construct()
    {
        $config = parse_ini_file($this->configPathFile);

        $this->host = $config['host'];
        $this->port = $config['port'];
        $this->sid = $config['sid'];
        $this->username = $config['username'];
        $this->password = $config['password'];
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    public function connect()
    {
        $this->conn = null;

        $connString = "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = " . $this->host . ")(PORT = " . $this->port . "))
                            (CONNECT_DATA = (SERVER = DEDICATED) (SERVICE_NAME = " . $this->sid . ")))";

        $opt = [  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_NUM,];
        try {
            $this->conn = new PDO("oci:dbname=" . $connString, $this->username, $this->password, $opt);
        } catch (PDOException $e) {
            echo($e->getMessage());
        }

        return $this->conn;
    }

    public function disconnect()
    {
        $this->conn = null;
    }

}

?>