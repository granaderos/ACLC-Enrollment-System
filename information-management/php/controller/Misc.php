<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 2/21/17
 * Time: 3:08 PM
 * To change this template use File | Settings | File Templates.
 */
include_once "DatabaseConnector.php";
class Misc extends DatabaseConnector {
    function getYear() {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT CURDATE();");

        $date = $sql->fetch();
        $this->closeConnection();

        $dateArr = explode("-", $date[0]);
        echo $dateArr[0];
    }

}