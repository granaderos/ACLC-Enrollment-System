<?php
/**
 * Created by PhpStorm.
 * User: Marejean
 * Date: 11/20/16
 * Time: 2:22 AM
 */
include_once "DatabaseConnector.php";
class Curriculum extends  DatabaseConnector {
    function addCurriculum($name, $desc) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("INSERT INTO curriculum VALUES (null, ?, ?);");
        $sql->execute(array($name, $desc));

        $this->closeConnection();
    }

    function displayCurriculum() {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT * FROM curriculum;");

        $data = "";
        while($curr = $sql->fetch()) {
            $data .= "<span class='glyphicon glyphicon-calendar'></span>&nbsp;&nbsp;<span style='font-size: 20px'>".$curr[1]."</span>";
            $data .= "<p class='text-justify' style='text-indent: 5em;'>Description: ".$curr[2]."</p>";
        }

        if($data == "") $data = "<p class='text-success text-justify' style='text-indent: 3em; !important'><span class='glyphicon glyphicon-chevron-right'></span> No curriculum yet;</p>";

        echo $data;

        $this->closeConnection();
    }

    function displayCurriculumOptions() {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT curriculum FROM curriculum");
        $sql->execute();

        $data = "";
        while($content = $sql->fetch()) {
            $data .= "<option value='".$content[0]."'>".$content[0]."</option>";
        }

        echo $data;

        $this->closeConnection();
    }
} 