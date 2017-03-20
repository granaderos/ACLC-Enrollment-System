<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 1/23/17
 * Time: 8:36 PM
 * To change this template use File | Settings | File Templates.
 */
include_once "DatabaseConnector.php";
session_start();
class Admin extends DatabaseConnector {

    function addStuff($firstName, $middleName, $lastName, $username, $type) {
        $this->openConnection();
        //$password = md5(substr($firstName, 0, 1).substr($middleName, 1, 1));
        //if(strlen($password) > 8) $password = substr($password, 0, 8);
        $sql = $this->dbHolder->prepare("INSERT INTO staff VALUES (null, ?, ?, ?, ?, password(?), ?, 0);");
        $sql->execute(array($firstName, $middleName, $lastName, $username, $username, $type));

        //echo $password;

        $this->closeConnection();
    }

    function loginStaff($username, $password) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT * FROM staff WHERE username = ? AND password = password(?);");
        $sql->execute(array($username, $password));

        $this->closeConnection();

        if($data = $sql->fetch()) {
            $_SESSION["staffId"] = $data[0];
            $_SESSION["staffUsername"] = $data[4];
            $_SESSION["lastname"] = $data[1];
            $_SESSION["firstname"] = $data[2];
            $_SESSION["middlename"] = $data[3];
            $_SESSION["type"] = $data[6];

            echo $data[6];
        } else echo "invalid";

    }

    function adminViewStaff() {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT * FROM staff WHERE type != 'admin' ORDER BY type, lastname;");

        $data = "";

        while($content = $sql->fetch()) {
            $data .= "<tr>
                            <td>".$content[1].", ".$content[2]." ".$content[3]."</td>
                            <td>".$content[6]."</td>
                      </tr>";
        }
        $this->closeConnection();

        if($data == "") $data = "<tr class='alert alert-info'><td>No records yet;</td></tr>";
        else $data = "<tr>
                            <th>Name</th>
                            <th>Designation</th>
                      </tr>".$data;
        echo $data;
    }

    function hrViewStaff(){
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT * FROM staff WHERE staffId != ? AND type != 'admin' ORDER BY type, lastname;");
        $sql->execute(array($_SESSION["staffId"]));

        $data = "";

        while($content = $sql->fetch()) {
            $data .= "<tr id='hrStaffId".$content[0]."'>
                            <td id='hrStaffName".$content[0]."'>".$content[1].", ".$content[2]." ".$content[3]."</td>
                            <td>".$content[6]."</td>
                            <td>
                                <a data-toggle='modal' class='btn btn-primary' href='#divHrEditStaff' onclick=\"hrEditStaff('".$content[0]."')\"><span aria-hidden=true class='glyphicon glyphicon-edit pointer' title='edit'></span>&nbsp;edit</a>

                                <button onclick='hrDeleteStaff(".$content[0].")' class='btn btn-primary'><span class='glyphicon glyphicon-trash'></span>&nbsp;delete</button>
                            </td>
                      </tr>";
        }
        $this->closeConnection();

        if($data == "") $data = "<tr class='alert alert-info'><td>No records yet;</td></tr>";
        else $data = "<tr>
                            <th>Name</th>
                            <th>Designation</th>
                            <th></th>
                      </tr>".$data;
        echo $data;
    }

    function hrEditStaff($staffId) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT * FROM staff WHERE staffId = ?;");
        $sql->execute(array($staffId));

        if($content = $sql->fetch()) {
            $data = array("lastname"=>$content[1], "firstname"=>$content[2], "middlename"=>$content[3]);
            echo json_encode($data);
        } else echo "none";

        $this->closeConnection();
    }

    function hrSaveStaff($staffId, $lastName,$firstName, $middleName) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("UPDATE staff SET lastName = ?, firstName = ?, middleName = ? WHERE staffId = ?;");
        $sql->execute(array($lastName, $firstName, $middleName, $staffId));

        $this->closeConnection();
    }

    function hrDeleteStaff($staffId) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("DELETE FROM staff WHERE staffId = ?;");
        $sql->execute(array($staffId));

        $this->closeConnection();
    }
}