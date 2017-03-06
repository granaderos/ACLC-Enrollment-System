<?php

class DatabaseConnector {
    protected $dbHost = "mysql:host=localhost;";
    protected $dbName = "dbname=aclcdata";
    protected $dbUser = "root";
    protected $dbPass = "";
    protected $dbHolder;

    protected function openConnection() {
        $this->dbHolder = new PDO($this->dbHost.$this->dbName, $this->dbUser, $this->dbPass);
    }

    protected function closeConnection() {
        $this->dbHolder = null;
    }
}