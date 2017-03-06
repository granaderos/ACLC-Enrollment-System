<?php
/**
 * Created by PhpStorm.
 * User: Marejean
 * Date: 10/23/16
 * Time: 9:39 PM
 */
include_once "DatabaseConnector.php";
session_start();
class Programs extends DatabaseConnector {
    function addProgram($code, $description) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("INSERT INTO programs VALUES (?, ?);");
        $sql->execute(array(htmlentities($code), htmlentities($description)));

        $this->closeConnection();
        $_SESSION["programCode"] = $code;
        echo $_SESSION["programCode"]." (".$description.")";
    }

    function addCourse($year, $sem, $curYear, $cur, $code, $description, $unit, $preReq) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("INSERT INTO courses VALUES (?, ?, ?);");
        $sql->execute(array(htmlentities($code), htmlentities($description), htmlentities($unit)));

        if($cur == "Semester") {
            $sql1 = $this->dbHolder->prepare("INSERT INTO coursestoprogramssemestral VALUES (null, ?, ?, ?, ?, ?);");
            $sql1->execute(array(htmlentities($_SESSION["programCode"]), htmlentities($code), $year, $sem, htmlentities($curYear)));
        } else {
            $sql1 = $this->dbHolder->prepare("INSERT INTO coursestoprogramstrimestral VALUES (null, ?, ?, ?, ?, ?);");
            $sql1->execute(array(htmlentities($_SESSION["programCode"]), htmlentities($code), $year, $sem, htmlentities($curYear)));
        }

        if($preReq != "none") {
            $preqs = explode(",", $preReq);
            for($i = 0; $i < count($preqs); $i++) {
                $sql2 = $this->dbHolder->prepare("INSERT INTO prerequisites VALUES (?, ?);");
                $sql2->execute(array(htmlentities($code), $preqs[$i]));
            }
        }

        $this->closeConnection();
    }

    function getWordYear($year) {
        $word = "";
        switch($year) {
            case 1: $word = "1st"; break;
            case 2: $word = "2nd"; break;
            case 3:  $word = "3rd"; break;
            case 4: $word = "4th"; break;

        }
        return $word;

    }

    function editProgram($code, $newCode, $description) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("UPDATE programs SET progCode = ?, description = ? WHERE progCode = ?");
        $sql->execute(array($newCode, $description, $code));

        $this->closeConnection();
    }

    function deleteProgram($code) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("DELETE FROM programs WHERE progCode = ?;");
        $sql->execute(array($code));

        $this->closeConnection();
    }

    function displayPrograms() {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT * FROM programs ORDER BY description ASC;");

        $data = "";

        while($content = $sql->fetch()) {
            $data .= "<tr id='tr".$content[0]."'>
                            <td>
                                <a onclick=\"setProgramSession('".$content[0]."', '".$content[1]."')\" href='courses' title='click to display courses' id='prog".$content[0]."'>".$content[1]."</a>
                            </td>
                            <td>
                                <a data-toggle='modal' href='#editProgram' onclick=\"setEditProgram('".$content[0]."')\"><span aria-hidden=true class='glyphicon glyphicon-edit pointer' title='edit'></span></a>
                            </td>
                            <td>
                                <a href='#' onclick=\"deleteProgram('".$content[0]."')\"><span class='glyphicon glyphicon-trash' title='delete'></span></a>
                            </td>
                      </tr>";
        }

        if($data == "") $data = "No programs added yet;";
        else $data = "<table class='table table-hover'>".$data."</table>";

        echo "<h3>Program Offers</h3>".$data;

        /*$sql = $this->dbHolder->query("SELECT DISTINCT p.* FROM programs p, curriculum cu, coursestoprogramssemestral cp
                                       WHERE p.progCode = cp.progCode
                                       AND cu.curriculum = cp.curriculumYear;");

        $data = "<h2><input style='width: 25px; height: 25px;' type='radio' name='radioProgToDisplay'  checked/> Semestral &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     <input style='width: 25px; height: 25px;' type='radio' name='radioProgToDisplay' onchange='displayProgramsTrimestral()'/> Trimestral
                 </h2>";
        while($programs = $sql->fetch()) {
            $data .= "<h4 class='alert alert-info'>".$programs[0]." - ".$programs[1]." <span class='glyphicon glyphicon-edit pull-right' title='Edit' data-toggle='modal' href='#report'></span></h4>";
            $sql1 = $this->dbHolder->prepare("SELECT DISTINCT cp.year FROM coursestoprogramssemestral cp, curriculum cu
                                              WHERE cu.curriculum = cp.curriculumYear
                                              AND cp.progCode = ?
                                              AND cp.curriculumYear = ?;");
            $sql1->execute(array($programs[0], $curriculum));
            $data .= "<table class='table table-bordered table-hover'>";
            while($year = $sql1->fetch()) {
                $sql2 = $this->dbHolder->prepare("SELECT DISTINCT cp.semester FROM coursestoprogramssemestral cp, curriculum cu
                                                  WHERE cu.curriculum = cp.curriculumYear
                                                  AND cp.progCode = ?
                                                  AND cp.curriculumYear = ?;");
                $sql2->execute(array($programs[0], $curriculum));
                while($semester = $sql2->fetch()) {
                    $data .= "<tr class='alert alert-danger'><th colspan='4'>".$this->getWordYear($year[0])." Year, ".$this->getWordYear($semester[0])." Semester</th></tr>";
                    $data .= "<tr>
                                <th>Course Code</th>
                                <th>Description</th>
                                <th>Unit</th>
                                <th>Pre-requisites</th>
                              </tr>";
                    $sql3 = $this->dbHolder->prepare("SELECT c.courseCode, c.description, c.units
                                                      FROM courses c, coursestoprogramssemestral cp, curriculum cu
                                                      WHERE c.courseCode = cp.courseCode
                                                      AND cu.curriculum = cp.curriculumYear
                                                      AND cp.progCode = ?
                                                      AND cp.semester = ?
                                                      AND cp.year = ?
                                                      AND cp.curriculumYear = ?;");
                    $sql3->execute(array($programs[0], $semester[0], $year[0], $curriculum));
                    while($courses = $sql3->fetch()) {
                        $data .=
                                "<tr>
                                    <td>".$courses[0]."</td>
                                    <td>".$courses[1]."</td>
                                    <td>".$courses[2]."</td>
                                    <td></td>
                                </tr>";
                    }
                }
            }
            $data .= "</table>";
        }
        echo $data;
        echo "display prod this c = ".$curriculum;*/

        $this->closeConnection();
    }

    function displayCourses($curriculum, $sem) {
        $this->openConnection();
        $progCode = $_SESSION["progCode"];
        $table = "coursestoprogramstrimestral";
        if($sem == "Semestral") $table = "coursestoprogramssemestral";


        $data = "";

        $sql = $this->dbHolder->prepare("SELECT * FROM programs WHERE progCode = ?;");
        $sql->execute(array($progCode));


        $program = $sql->fetch();
        $_SESSION["program"] = $program[1];

        $data .= "<h4 class='alert alert-info'>" . $program[0] . " - " . $program[1] . "</h4>";
        $sql1 = $this->dbHolder->prepare("SELECT DISTINCT cp.year FROM ".$table." cp, curriculum cu
                                              WHERE cu.curriculum = cp.curriculumYear
                                              AND cp.progCode = ?
                                              AND cp.curriculumYear = ?;");
        $sql1->execute(array($program[0], $curriculum));
        $data .= "<table class='table table-bordered table-hover'>";
        $progData = "";
        while ($year = $sql1->fetch()) {
            $sql2 = $this->dbHolder->prepare("SELECT DISTINCT cp.semester FROM ".$table." cp, curriculum cu
                                                  WHERE cu.curriculum = cp.curriculumYear
                                                  AND cp.progCode = ?
                                                  AND cp.curriculumYear = ?;");
            $sql2->execute(array($program[0], $curriculum));
            while ($semester = $sql2->fetch()) {
                $progData .= "<tr class='alert alert-danger'><th colspan='4'>" . $this->getWordYear($year[0]) . " Year, " . $this->getWordYear($semester[0]) . " Semester</th></tr>";
                $progData .= "<tr>
                                <th>Course Code</th>
                                <th>Description</th>
                                <th>Unit</th>
                                <th>Pre-requisites</th>
                              </tr>";
                $sql3 = $this->dbHolder->prepare("SELECT c.courseCode, c.description, c.units
                                                      FROM courses c, ".$table." cp, curriculum cu
                                                      WHERE c.courseCode = cp.courseCode
                                                      AND cu.curriculum = cp.curriculumYear
                                                      AND cp.progCode = ?
                                                      AND cp.semester = ?
                                                      AND cp.year = ?
                                                      AND cp.curriculumYear = ?;");
                $sql3->execute(array($program[0], $semester[0], $year[0], $curriculum));
                $coursesData = "";
                while ($courses = $sql3->fetch()) {

                    $sql4 = $this->dbHolder->prepare("SELECT pre.* FROM prerequisites pre, courses c
                                                WHERE c.courseCode = pre.courseCode
                                                AND c.courseCode = ?;");
                    $sql4->execute(array($courses[0]));

                    $preqData = "";
                    $c = 0;
                    while($preqsCon = $sql4->fetch()) {
                        if($c > 0) $preqData .= ", ";
                        $preqData .= $preqsCon[0];
                        $c++;
                    }
                    if($preqData == "") $preqData = "none";

                    $coursesData .=
                        "<tr>
                            <td>" . $courses[0] . "</td>
                            <td>" . $courses[1] . "</td>
                            <td>" . $courses[2] . "</td>
                            <td>".$preqData."</td>
                        </tr>";
                }
                $progData .= $coursesData;
            }
        }
        if($progData == "") {
            $data .= "No courses added to this program (".$progCode.") yet for ".$sem." and ".$curriculum." curriculum; click <a href='add-courses'>HERE</a> to set courses.";
            $_SESSION["progCode"] = $progCode;
            $_SESSION["progDescription"] = $program[1];
        } else $data .= $progData;
        $data .= "</table>";
        echo $data;

        $this->closeConnection();
    }

    function addTempCourseToProgram($code, $description, $unit, $labUnit, $progCur, $curYear, $curSem, $preReqs) {
        $this->openConnection();
        $message = "";
        $sql = $this->dbHolder->prepare("SELECT * FROM courses WHERE courseCode = ?;");
        $sql->execute(array($code));



        if($courseData = $sql->fetch()) {
            $sql3 = $this->dbHolder->prepare("SELECT * FROM tempCoursesToProgram WHERE courseCode = ?;");
            $sql3->execute(array($code));

            if($sql3->fetch()) {
                $message = $code." is already on the list.";
            } else {
                if(strtoupper($courseData[1]) == strtoupper($description)) {
                    // proceed adding
                    $sql1 = $this->dbHolder->prepare("INSERT INTO tempCoursesToProgram VALUES (?, ?, ?, ?, ?);");
                    $sql1->execute(array($_SESSION["progCode"], $code, $curYear, $curSem, $progCur));

                } else {
                    $message = "The course code ".$code." already exist with the course description ".$courseData[1];
                }
            }
        } else {
            $sql2 = $this->dbHolder->prepare("INSERT INTO courses VALUES (?, ?, ?, ?);");
            $sql2->execute(array($code, $description, $unit, $labUnit));
            // then add to tempCoursesToProgram table
            $sql1 = $this->dbHolder->prepare("INSERT INTO tempCoursesToProgram VALUES (?, ?, ?, ?, ?);");
            $sql1->execute(array($_SESSION["progCode"], $code, $curYear, $curSem, $progCur));

            if($preReqs != "none") {
                for($i = 0; $i < sizeof($preReqs); $i++) {
                    $sql4 = $this->dbHolder->prepare("INSERT INTO prerequisites VALUES (?, ?);");
                    $sql4->execute(array($code, $preReqs[$i]));
                }
            }
        }

        echo $message;
        $this->closeConnection();
    }

    function saveCourses($progSem) {
        $this->openConnection();
        $table = "coursestoprogramstrimestral";
        if($progSem == "Semester") $table = "coursestoprogramssemestral";
        $sql = $this->dbHolder->query("SELECT * FROM tempCoursesToProgram");

        $data = "";
        $counter = 0;
        while($tempData = $sql->fetch()) {
            $sql1 = $this->dbHolder->prepare("INSERT INTO ".$table." VALUES (null, ?, ?, ?, ?, ?);");
            $sql1->execute(array($tempData[0], $tempData[1], $tempData[2], $tempData[3], $tempData[4]));

            if($counter == 0) {
                $data .= "<tr class='alert alert-danger'>
                            <th colspan='4' class='text-center'>Year: [ ".$tempData[2]." ]   |    Semester: [ ".$tempData[3]." ] </th>
                          </tr>
                          <tr class='alert alert-info'>
                            <th>Course Code</th>
                            <th>Description</th>
                            <th>Units</th>
                            <th>Pre-requisite(s)</th>
                          </tr>";
            }
            $counter++;

            $sql4 = $this->dbHolder->prepare("SELECT pre.* FROM prerequisites pre, courses c
                                                WHERE c.courseCode = pre.courseCode
                                                AND c.courseCode = ?;");
            $sql4->execute(array($tempData[1]));

            $preqData = "";
            $c = 0;
            while($preqsCon = $sql4->fetch()) {
                if($c > 0) $preqData .= ", ";
                $preqData .= $preqsCon[0];
                $c++;
            }
            if($preqData == "") $preqData = "none";

            // select description
            $sql3 = $this->dbHolder->prepare("SELECT description, units, labUnits FROM courses WHERE courseCode = ?;");
            $sql3->execute(array($tempData[1]));
            $desc = $sql3->fetch();

            $unit = $desc[1];
            if($desc[2] > "0") $unit .= ("/".$desc[2]);

            $data .= "<tr>
                        <td>".$tempData[1]."</td>
                        <td>".$desc[0]."</td>
                        <td>".$unit."</td>
                        <td>".$preqData."</td>
                      </tr>";
        }

        $sql2 = $this->dbHolder->prepare("DELETE FROM tempCoursesToProgram;");
        $sql2->execute();

        $this->closeConnection();
        echo $data;
    }

    function displayProgramsTrimestral($curriculum) {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT * FROM programs;");

        $data = "<h2><input style='width: 25px; height: 25px;' type='radio' name='radioProgToDisplay' onchange='displayPrograms()' /> Semestral &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     <input style='width: 25px; height: 25px;' type='radio' name='radioProgToDisplay' checked /> Trimestral
                 </h2>";
        while($programs = $sql->fetch()) {
            $data .= "<h4 class='alert alert-info'>".$programs[0]." - ".$programs[1]." <span class='glyphicon glyphicon-edit pull-right' title='Edit' data-toggle='modal' href='#report'></span></h4>";
            $sql1 = $this->dbHolder->prepare("SELECT DISTINCT year FROM coursestoprogramstrimestral WHERE progCode = ?;");
            $sql1->execute(array($programs[0]));
            $data .= "<table class='table table-bordered table-hover'>";
            while($year = $sql1->fetch()) {
                $sql2 = $this->dbHolder->prepare("SELECT DISTINCT semester FROM coursestoprogramstrimestral WHERE progCode = ?;");
                $sql2->execute(array($programs[0]));
                while($semester = $sql2->fetch()) {
                    $data .= "<tr class='alert alert-danger'><th colspan='4'>".$this->getWordYear($year[0])." Year, ".$this->getWordYear($semester[0])." Trimester</th></tr>";
                    $data .= "<tr>
                                <th>Course Code</th>
                                <th>Description</th>
                                <th>Unit</th>
                                <th>Pre-requisites</th>
                              </tr>";
                    $sql3 = $this->dbHolder->prepare("SELECT c.courseCode, c.description, c.units
                                                      FROM courses c, coursestoprogramstrimestral cp
                                                      WHERE c.courseCode = cp.courseCode
                                                      AND cp.progCode = ?
                                                      AND cp.semester = ?
                                                      AND cp.year = ?
                                                      AND cp.curriculumYear = ?;");
                    $sql3->execute(array($programs[0], $semester[0], $year[0], $curriculum));
                    while($courses = $sql3->fetch()) {
                        $data .=
                            "<tr>
                                <td>".$courses[0]."</td>
                                    <td>".$courses[1]."</td>
                                    <td>".$courses[2]."</td>
                                    <td></td>
                                </tr>";
                    }
                }
            }
            $data .= "</table>";
        }
        echo $data;

        $this->closeConnection();
    }

    function displayPossiblePrerequisites($curriculum, $semType, $year, $sem) {
        $this->openConnection();
        $table = "coursestoprogramstrimestral";
        if($semType == "Semester")
            $table = "coursestoprogramssemestral";

        $statement = "SELECT c.courseCode FROM courses c, programs p, curriculum cur, ".$table." cp
                WHERE c.courseCode = cp.courseCode
                AND p.progCode = cp.progCode
                AND cur.curriculum = cp.curriculumYear
                AND cp.curriculumYear = ?
                AND cp.progCode = ?
                AND (cp.year <= ? AND cp.semester <= ?);";
        $sql = $this->dbHolder->prepare($statement);
        $sql->execute(array($curriculum, $_SESSION["progCode"], $year, $sem));

        $data = "<option></opion>";
        while($content = $sql->fetch()) {
            $data .= "<option value='".$content[0]."'>
                        ".$content[0]."
                      </option>";
        }
        $this->closeConnection();
        echo $data;
    }

    function retrieveCourses($curriculum, $courseDivision, $program) {
        $this->openConnection();
        $table = "coursestoprogramstrimestral";
        if($courseDivision == "Semester") $table = "coursestoprogramssemestral";

        $sql = $this->dbHolder->prepare("SELECT c.courseCode, c.description, c.units, cp.* FROM courses c, ".$table." cp
                                         WHERE c.courseCode = cp.courseCode AND cp.curriculumYear = ? AND cp.progCode = ?;");
        $sql->execute(array($curriculum, $program));

        $data = "<tr>
                    <th>Course Code</th>
                    <th>Description</th>
                    <th>Units</th>
                 </tr>";
        while($content = $sql->fetch()) {
            $data .= "<tr id='".$content[0]."'>
                        <td id='courseCode".$content[0]."'>".$content[0]."</td>
                        <td id='description".$content[0]."'>".$content[1]."</td>
                        <td id='units".$content[0]."'>".$content[2]."</td>
                        <td><button onclick=\"saveCourseToNewCourseDivision('".$content[0]."')\">Add Course</button></td>
                      </tr>";
        }

        echo $data;

        $this->closeConnection();
    }

    function addCourseToProgram($courseCode, $program, $curriculum, $year, $sem, $courseDivision) {
        $this->openConnection();
        $table = "coursestoprogramstrimestral";
        if($courseDivision == "Semester") $table = "coursestoprogramssemestral";


        $sql = $this->dbHolder->prepare("INSERT INTO ".$table." VALUES (null, ?, ?, ?, ?, ?);");
        $sql->execute(array($program, $courseCode, $year, $sem, $curriculum));

        echo "table = ".$table;

        $this->closeConnection();
    }

    function displayProgramsForDean() {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT * FROM programs ORDER BY progCode ASC;");

        $data = "";

        while($content = $sql->fetch()) {
            $data .= "<tr id='tr".$content[0]."'>
                            <td>
                                <a style='color: black;' onclick=\"displaySectionDiv('".$content[0]."', '".$content[1]."')\" class='makeTooltip' title='click to display courses' id='prog".$content[0]."'>".$content[0]." - ".$content[1]."</a>
                            </td>
                      </tr>";
        }
        $this->closeConnection();
        if($data == "") {
            $data =  "<tr><td><p class='alert alert-danger'>No programs added yet;</p></td></tr>";
        }
        echo "<table class='table table-responsive table-striped'>".$data."</table>";
    }
} 