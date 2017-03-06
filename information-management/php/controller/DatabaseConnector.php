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
/*
<br /><b>Fatal error</b>:  Uncaught PDOException: SQLSTATE[HY000] [1049]
Unknown database 'aclcData' in /opt/lampp/htdocs/aclc/php/controller/DatabaseConnector.php:11Stack
trace:#0 /opt/lampp/htdocs/aclc/php/controller/DatabaseConnector.php(11): PDO-&gt;__construct('mysql:host=loca...', 'root', '')
#1 /opt/lampp/htdocs/aclc/php/controller/Students.php(11): DatabaseConnector-&gt;openConnection()
#2 /opt/lampp/htdocs/aclc/php/students/displayNewStudentID.php(10): Students-&gt;displayNewStudentID()
#3 {main}  thrown in <b>/opt/lampp/htdocs/aclc/php/controller/DatabaseConnector.php</b> on line <b>11</b><br />
*/
