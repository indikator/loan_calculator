<?php

class Db
{
    private $hostname;
    private $dbname;
    private $username;
    private $password;
    private $connection;

    public function __construct($input)
    {
        $this->hostname = $input['hostname'];
        $this->dbname = $input['dbname'];
        $this->username = $input['username'];
        $this->password = $input['password'];

        try {
            $this->connection = new PDO("mysql:host={$input['hostname']};dbname={$input['dbname']}", $input['username'], $input['password']);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            exit("Connection failed: " . $e->getMessage() . "\n");
        }
    }

    public function __destruct()
    {
        $this->connection = null;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}