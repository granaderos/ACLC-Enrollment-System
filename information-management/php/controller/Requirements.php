<?php
/**
 * Created by PhpStorm.
 * User: Marejean
 * Date: 10/23/16
 * Time: 1:01 AM
 */
include_once "DatabaseConnector.php";
class Requirements extends  DatabaseConnector {
    function displayStudentRequirements() {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT * FROM requirements ORDER BY requirementId DESC;");

        $data = "";
        while($content = $sql->fetch()) {
            $data .=
                "<tr>
                    <td><a onclick='deleteRequirement(".$content[0].")' class='glyphicon glyphicon-remove' onmouseover='$(this).tooltip();' title='Click to delete ".$content[1]." from the list.'></a></td>
                    <td ondblclick='editRequirement(".$content[0].")' id='req".$content[0]."' title='Double click to edit this requirements.'>".$content[1]."</td>
                    <td ondblclick='editRequirementFor(".$content[0].")' id='reqFor".$content[0]."' title='Double click to edit this.'>".$content[2]."</td>
                </tr>";
        }
        $this->closeConnection();
        if($data != "") {
            $data =
                "<tr>
                    <th>Action</th>
                    <th>Requirements</th>
                    <th></th>
                </tr>".$data;
        } else {
            $data =
                "<tr>
                    <th>No results;</th>
                </tr>";
        }
        echo $data;
    }

    function addRequirement($req, $forWhom) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("INSERT INTO requirements VALUES (null, ?, ?);");
        $sql->execute(array(htmlentities($req), htmlentities($forWhom)));
        $this->closeConnection();
        $this->displayStudentRequirements();
    }

    function updateRequirement($id, $newReq) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("UPDATE requirements SET description = ? WHERE requirementID = ?;");
        $sql->execute(array(htmlentities($newReq), $id));

        $this->closeConnection();
    }

    function updateRequirementFor($id, $newReqFor) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("UPDATE requirements SET requiree = ? WHERE requirementId = ?;");
        $sql->execute(array(htmlentities($newReqFor), $id));

        $this->closeConnection();
    }

    function deleteRequirement($id) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("DELETE FROM requirements WHERE requirementId = ?;");
        $sql->execute(array($id));

        $this->closeConnection();
        $this->displayStudentRequirements();
    }
} 