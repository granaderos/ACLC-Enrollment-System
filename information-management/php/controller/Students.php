<?php
/**
 * Created by PhpStorm.
 * User: Marejean
 * Date: 10/23/16
 * Time: 3:29 PM
 */
include_once "DatabaseConnector.php";
session_start();
class Students extends DatabaseConnector {
    function displayNewStudentID() {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT MAX(id) FROM students;");
        $idContent = $sql->fetch();
        $id = 1;
        if($idContent != NULL OR $idContent[0] > 0) {
            $id = $idContent[0]+1;
        }
        $date = $this->getCurrentDate();
        $this->closeConnection();
        $dateArr = explode("-", $date);
        $firstTwo = substr($dateArr[0], strlen($dateArr[0])-2, 2);
        $id .= "";
        switch(strlen($id)) {
            case 1: $id = $firstTwo."000".$id; break;
            case 2: $id = $firstTwo."00".$id; break;
            case 3: $id = $firstTwo."0".$id; break;
            case 4: $id = $firstTwo.$id; break;
            default: $id = $firstTwo.substr($id, strlen($id)-4, 4);
        }
        echo $id;
    }

    function displayStudentRequirements() {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT * FROM requirements;");
        $data = "";
        while($content = $sql->fetch()) {
            $data .= ("<tr id='".$content[0]."'>
                            <td>
                                <input type='checkbox' id='checkReq".$content[0]."' value='".$content[0]."' />
                                ".$content[1]."
                            </td>
                        </tr>");
        }
        if($data == "") $data = "No requirements added yet; please set the requirements to be submitted by students";
        $this->closeConnection();
        echo $data;
    }

    function displayAvailablePrograms() {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT * FROM programs ORDER BY progCode;");
        $data = "";
        while($content = $sql->fetch()) {
            $data .= "<option value='".$content[0]."'>
                            (".$content[0].") ".$content[1]."
                      </option>";
        }
        if($data == "") $data = "<option>No programs available;</option>";
        $this->closeConnection();
        echo "<option></option>".$data;
    }

    function displayAvailableSections($program) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT s.sectionId, s.sectionCode FROM sections s, programs p, config c
                                         WHERE p.progCode = s.programCode
                                         AND p.progCode = ?
                                         AND s.year = c.year
                                         AND s.semester = c.sem
                                         AND s.type='block';");
        $sql->execute(array($program));

        $data = "";
        while($sec = $sql->fetch()) {
            $data .= "<option value=".$sec[0].">".$sec[1]."</option>";
        }

        if($data == "") $data = "<option value=-1>No available section yet for ".$program."</option>";
        echo $data;
        $this->closeConnection();
    }

    function displayAvailableCurriculum() {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT * FROM curriculum ORDER BY curriculum;;");
        $data = "";
        while($content = $sql->fetch()) {
            $data .= "<option value='".$content[1]."'>
                            ".$content[1]."
                      </option>";
        }
        if($data == "") $data = "<option>No curriculum available;</option>";
        $this->closeConnection();
        echo $data;
    }

    function enrollStudent($firstName, $middleName, $lastName, $id, $program, $curriculum, $year, $type, $reqsIds, $section) {
        $this->openConnection();

        $sql5 = $this->dbHolder->prepare("SELECT * FROM students WHERE firstname = ? AND middlename = ? AND lastname = ?;");
        $sql5->execute(array($firstName, $middleName, $lastName));

        if($sql5->fetch()) {
            echo "exist";
        } else {
            $username = str_replace(" ", "_", $lastName).substr($firstName, 0, 1).$middleName[strlen($middleName)-1];
            $password = md5($this->getCurrentDate());
            if(strlen($password) > 8) $password = substr($password, 0, 8);

            $sql = $this->dbHolder->prepare("INSERT INTO students VALUES(null, ?, ?, ?, ?, null, null, null, null, null, null,
                  null, null, null, null, null, null, ?, ?, ?, ?, ?, ?, ?, null, null, null, null, null, null);");
            $sql->execute(array($id, $lastName, $firstName, $middleName, $year, $program, $curriculum,
                $this->getCurrentDate(), $type, $username, $password));

            if($reqsIds != "none") {
                for($i = 0; $i < count($reqsIds); $i++) {
                    $sql1 = $this->dbHolder->prepare("INSERT INTO studentrequirements VALUES (?, ?, 1, null, ?);");
                    $sql1->execute(array($id, $reqsIds[$i], $this->getCurrentDate()));
                }
            }

            //select curriculum first
            $sql3 = $this->dbHolder->query("SELECT * FROM config;");
            $config = $sql3->fetch();
            $table = "coursestoprogramstrimestral";
            if($config[1] == "Semestral") $table = "coursestoprogramssemestral";

            $sql4 = $this->dbHolder->prepare("SELECT courseCode FROM ".$table."
                                            WHERE progCode = ?
                                            AND curriculumYear = ?
                                            AND year = ?
                                            AND semester = 1;");
            $sql4->execute(array($program, $config[0], $year));

            while($courses = $sql4->fetch()) {
                //add block schedule
                $sql2 = $this->dbHolder->prepare("INSERT INTO studentSchedule VALUES (null, ?, ?, ?);");
                $sql2->execute(array($id, $courses[0], $section));

                // add into studentgrades table
                $sql6 = $this->dbHolder->prepare("INSERT INTO studentgrades VALUES (?, ?, ?, ?, ?, 0, 0, 0, 0, null);");
                $sql6->execute(array($id, $courses[0], $config[6], $year, $config[5]));

            }

            $this->closeConnection();

            echo "New students was successfully enrolled! \nDefault credentials are: \n\nusername: ".$username."\npassword: ".$password;
        }
    }

    function getRecentlyEnrolledStudents() {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT * FROM students ORDER BY id DESC limit 10;");

        $data = "";
        while($content = $sql->fetch()) {
            $data .= "<li><a onclick='setStudentSession(".$content[1].")'>".$content[2].", ".$content[3]." ".$content[4]." (".$content[18].")</a></li>";
        }

        $this->closeConnection();

        if($data == "") $data = "No record found;";
        else $data = "<ul>".$data."</ul>";
        echo $data;
    }

    function getStudentInfo() {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT s.*, p.* FROM students s, programs p
                                         WHERE s.progCode = p.progCode AND s.studentId = ?;");
        $sql->execute(array($_SESSION["studentId"]));

        if($content = $sql->fetch()) {
            $age = "---";
            if($content[8] != NULL)
                $age = date_diff($this->getCurrentDate(), $content[8]);
            $data = array("studentId"                      =>$content[1],
                           "lastname"                      =>$content[2],
                           "firstname"                     =>$content[3],
                           "middlename"                    =>$content[4],
                           "address"                       =>$content[5],
                           "emailAddress"                  =>$content[6],
                           "contactNumber"                 =>$content[7],
                           "nationality"                   =>$content[8],
                           "birthday"                      =>$content[9],
                           "gender"                        =>$content[10],
                           "placeOfBirth"                  =>$content[11],
                           "secondarySchool"               =>$content[12],
                           "secDateGraduated"              =>$content[13],
                           "schoolLastAttended"            =>$content[14],
                           "schoolLastAttendedDateAttended"=>$content[15],
                           "programDateCompleted"          =>$content[16],
                           "yearLevel"                     =>$content[17],
                           "progCode"                      =>$content[18],
                           "curriculum"                    =>$content[19],
                           "dateEnrolled"                  =>$content[20],
                           "type"                          =>$content[21],
                           "gfName"                        =>$content[24],
                           "gmName"                        =>$content[25],
                           "glName"                        =>$content[26],
                           "gRelationship"                 =>$content[27],
                           "gAddress"                      =>$content[28],
                           "gContactNumber"                =>$content[29],
                           "program"                       =>$content[31],
                           "age"                           =>$age
            );
        }
        echo json_encode($data);

        $this->closeConnection();
    }

    function getCurrentDate() {
        $sql = $this->dbHolder->query("SELECT CURDATE();");
        return $sql->fetch()[0];
    }
} 