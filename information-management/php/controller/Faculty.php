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
class Faculty extends DatabaseConnector {

    function displayTodaySched() {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT DAYOFWEEK(NOW());");
        $index = $sql->fetch();
        $day = $this->getDayName($index[0]);

        $sql1 = $this->dbHolder->prepare("SELECT sc.day, sc.timeStart, sc.timeEnd, sc.room, sc.courseCode FROM schedule sc, sections se, professorSchedule ps, config c
                                            WHERE se.sectionId = sc.sectionId
                                            AND se.sectionId = ps.sectionId
                                            AND sc.courseCode = ps.courseCode
                                            AND sc.day = ?
                                            AND ps.profId = ?
                                            ORDER BY sc.timeStart;");
        $sql1->execute(array($day, $_SESSION["staffId"]));

        $data = "";
        while($sched = $sql1->fetch()) {
            $data .= "<tr>
                        <td>".$sched[4]."</td>
                        <td>".$sched[1]." - ".$sched[2]."</td>
                        <td>".$sched[3]."</td>
                      </tr>";
        }

        if($data == "") {
            $data = "<h4>No schedule for today;</h4>";
        } else {
            $data = "<table class='table table-responsive table-striped'>
                        <tr>
                            <th>Course</th>
                            <th>Time</th>
                            <th>Room</th>
                        </tr>
                        ".$data."
                      </table>";
        }

        echo json_encode(array("sched"=>$data, "day"=>$day));

        $this->closeConnection();
    }

    function displaySchedule() {
        $this->openConnection();


        $sql1 = $this->dbHolder->prepare("SELECT DISTINCT sc.day FROM schedule sc, sections se, professorSchedule ps, config c, days d
                                            WHERE se.sectionId = sc.sectionId
                                            AND se.sectionId = ps.sectionId
                                            AND se.sy = c.sy
                                            AND se.year = c.year
                                            AND se.semester = c.sem
                                            AND sc.courseCode = ps.courseCode
                                            AND sc.day = d.day
                                            AND ps.profId = ?
                                            ORDER BY d.id;");
        $sql1->execute(array($_SESSION["staffId"]));

        $data= "";
        while($day = $sql1->fetch()) {
            $data .= "<tr class='alert alert-info'><th colspan='3'>".$day[0]."</th></tr>";
            $sql = $this->dbHolder->prepare("SELECT sc.timeStart, sc.timeEnd, sc.room, sc.courseCode FROM schedule sc, sections se, professorSchedule ps, config c, days d
                                            WHERE se.sectionId = sc.sectionId
                                            AND se.sectionId = ps.sectionId
                                            AND se.sy = c.sy
                                            AND se.year = c.year
                                            AND se.semester = c.sem
                                            AND sc.courseCode = ps.courseCode
                                            AND sc.day = d.day
                                            and sc.day = ?
                                            AND ps.profId = ?
                                            ORDER BY d.id, sc.timeStart;");
            $sql->execute(array($day[0], $_SESSION["staffId"]));

            while($sched = $sql->fetch()) {
                $data .= "<tr>
                            <th>".$sched[3]."</th>
                            <th>".$sched[0]." - ".$sched[1]."</th>
                            <th>".$sched[2]."</th>
                          </tr>";
            }
        }

        if($data == "") $data = "<tr><td>No schedule yet;</td></tr>";
        else $data = "<table class='table table-responsive table-striped'>
                        <tr>
                            <th>Course</th>
                            <th>Time</th>
                            <th>Room</th>
                        </tr>
                        ".$data."
                      </table>";
        echo $data;
        $this->closeConnection();
    }

    function getClassList() {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT DISTINCT se.sectionId, se.sectionCode, sc.courseCode FROM schedule sc, sections se, professorSchedule ps, config c
                                            WHERE se.sectionId = sc.sectionId
                                            AND se.sectionId = ps.sectionId
                                            AND se.sy = c.sy
                                            AND se.year = c.year
                                            AND se.semester = c.sem
                                            AND sc.courseCode = ps.courseCode
                                            AND ps.profId = ?
                                            ORDER BY se.sectionCode, sc.courseCode;");
        $sql->execute(array($_SESSION["staffId"]));

        $data = "";
        while($li = $sql->fetch()) {
            $data .= "<li><a onclick=\"displayClassList('".$li[0]."', '".$li[2]."')\">".$li[1]." - ".$li[2]."</a></li>";
        }

        if($data == "") $data = "<h4>No assigned classes yet;</h4>";
        else $data = "<ul>".$data."</ul>";

        echo $data;

        $this->closeConnection();
    }

    function getDayName($index) {
        $days = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
        return $days[$index-1];
    }

    function displayClassList($sectionId, $courseCode) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT DISTINCT st.studentId, st.lastname, st.firstname, st.middlename, st.yearLevel, se.sectionCode, c.sy, c.year, c.sem FROM students st, studentSchedule ss, schedule sc, sections se, professorSchedule ps, config c
                                            WHERE st.studentId = ss.studentId
                                            AND se.sectionId = ss.sectionId
                                            AND se.sectionId = sc.sectionId
                                            AND se.sectionId = ps.sectionId
                                            AND se.sy = c.sy
                                            AND se.year = c.year
                                            AND se.semester = c.sem
                                            AND sc.courseCode = ps.courseCode
                                            AND se.sectionId = ?
                                            AND sc.courseCode  = ?
                                            AND ps.profId = ?
                                            ORDER BY st.lastname, st.firstname, st.middlename;");
        $sql->execute(array($sectionId, $courseCode, $_SESSION["staffId"]));

        $data = ""; $counter = 1;
        $sectionCode = ""; $year = ""; $sem = ""; $sy = "";
        while($stud = $sql->fetch()) {
            $data .= "<tr>
                            <td>".$counter."</td>
                            <th>".$stud[0]."</th>
                            <th>".$stud[1].", ".$stud[2]." ".$stud[3]."</th>
                            <th>".$stud[4]."</th>
                      </tr>";
            if($counter == 1) {
                $sectionCode = $stud[5];
                $sy = $stud[6];
                $year = $stud[7];
                $sem = $stud[8];
            }
            $counter++;
        }

        if($data == "") $data = "none";
        else $data = "<div style='margin-top: 50px;' class='container fluid'>
                        <div class='text-center'>
                            <h4><label>ACLC College of Gapan</label></hr>
                            <h4><label>S.Y. ".$sy."</label></h4>
                        </div>
                        <table class='table table-bordered table-responsive'>
                            <tr>
                                <td>Section: <label style='text-decoration: underline;'>".$sectionCode."</label></td>
                                <td>Year: <label style='text-decoration: underline'>".$year."</label></td>
                            </tr>
                            <tr>
                                <td>Course: <label style='text-decoration: underline'>".$courseCode."</label></td>
                                <td>Semester: <label style='text-decoration: underline'>".$sem."</label></td>
                            </tr>
                            <tr>
                                <td colspan='2'>Faculty: <label style='text-decoration: underline'>".$_SESSION['lastname'].", ".$_SESSION['firstname']." ".$_SESSION['middlename']."</label></td>
                            </tr>
                        </table>

                        <table class='table table-responsive table-bordered'>
                        <tr class='alert alert-info'>
                            <th></th>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Year Level</th>
                        </tr>
                        ".$data."
                      </table>";
        $_SESSION["classList"] = $data;
        $_SESSION["classSection"] = $sectionCode;
        $_SESSION["classCourse"] = $courseCode;
        $this->closeConnection();
    }

    function getSectionsToEncode() {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT DISTINCT se.sectionId, se.sectionCode, sc.courseCode FROM schedule sc, sections se, professorSchedule ps, config c
                                            WHERE se.sectionId = sc.sectionId
                                            AND se.sectionId = ps.sectionId
                                            AND se.sy = c.sy
                                            AND se.year = c.year
                                            AND se.semester = c.sem
                                            AND sc.courseCode = ps.courseCode
                                            AND ps.profId = ?
                                            ORDER BY se.sectionCode, sc.courseCode;");
        $sql->execute(array($_SESSION["staffId"]));

        $data = "";
        while($sec = $sql->fetch()) {
            $data .= "<tr>
                        <th><a onclick=\"setSessionForEncode(".$sec[0].", '".$sec[2]."')\">".$sec[1]." - ".$sec[2]."</a></th>
                      </tr>";
        }

        if($data == "") $data = "<h4>No classes assigned to you yet;</h4>";
        else $data = "<table class='table table-striped'>
                        <tr><td><label>Classes</label> (section - course) </td></tr>
                        ".$data."
                      </table>";
        echo $data;
        $this->closeConnection();
    }

    function displayEncodingForm() {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT DISTINCT st.studentId, st.lastname, st.firstname, st.middlename,
                                          se.sectionCode, c.sy, c.year, c.sem, sg.pGrade, sg.mGrade, sg.pfGrade, sg.fGrade
                                          FROM students st, studentSchedule ss, schedule sc, sections se, professorSchedule ps, config c, studentgrades sg
                                            WHERE st.studentId = ss.studentId
                                            AND se.sectionId = ss.sectionId
                                            AND sc.sectionId = ss.sectionId
                                            AND ss.sectionId = ps.sectionId
                                            AND se.sy = c.sy
                                            AND se.year = c.year
                                            AND se.semester = c.sem
                                            AND ss.courseCode = ps.courseCode
                                            AND ps.courseCode = sg.courseCode
                                            AND st.studentId = sg.studentId
                                            AND se.sectionId = ?
                                            AND sc.courseCode  = ?
                                            AND ps.profId = ?
                                            ORDER BY st.lastname, st.firstname, st.middlename;");
        $sql->execute(array($_SESSION["classSection"], $_SESSION["classCourse"], $_SESSION["staffId"]));

        $data = ""; $counter = 1;
        $sectionCode = ""; $year = ""; $sem = ""; $sy = "";
        while($stud = $sql->fetch()) {
            $data .= "<tr>
                            <td>".$counter."</td>
                            <th>".$stud[0]."</th>
                            <th>".$stud[1].", ".$stud[2]." ".$stud[3]."</th>
                            <th><form onsubmit=\"savepGrade(".$stud[0].", '".$_SESSION["classCourse"]."'); return false;\"><input id='pGrade".$stud[0]."' value=".$stud[8]." onblur=\"savepGrade(".$stud[0].", '".$_SESSION["classCourse"]."')\" class='pGradeInput form-control' style='width: 80px;' disabled/></form></th>
                            <th><form onsubmit=\"savemGrade(".$stud[0].", '".$_SESSION["classCourse"]."'); return false;\"><input id='mGrade".$stud[0]."' value=".$stud[9]." onblur=\"savemGrade(".$stud[0].", '".$_SESSION["classCourse"]."')\" class='mGradeInput form-control' style='width: 80px;' disabled /></form></th>
                            <th><form onsubmit=\"savepfGrade(".$stud[0].", '".$_SESSION["classCourse"]."'); return false;\"><input id='pfGrade".$stud[0]."' value=".$stud[10]." onblur=\"savepfGrade(".$stud[0].", '".$_SESSION["classCourse"]."')\" class='pfGradeInput form-control' style='width: 80px;' disabled /></form></th>
                            <th><form onsubmit=\"savefGrade(".$stud[0].", '".$_SESSION["classCourse"]."'); return false;\"><input id='fGrade".$stud[0]."' value=".$stud[11]." onblur=\"savefGrade(".$stud[0].", '".$_SESSION["classCourse"]."')\" class='fGradeInput form-control' style='width: 80px;' disabled /></form></th>

                      </tr>";
            if($counter == 1) {
                $sectionCode = $stud[4];
                $sy = $stud[5];
                $year = $stud[6];
                $sem = $stud[7];
            }
            $counter++;
        }

        if($data == "") $data = "<h4>Something went wrong;</h4>";
        else $data = "<div class='container-fluid'>
                        <div class='text-center'>
                            <h2>Student Grades</h2>
                        </div>
                        <table class='table table-bordered table-responsive'>
                            <tr>
                                <td>Section: <label style='text-decoration: underline;'>".$sectionCode."</label></td>
                                <td>Year: <label style='text-decoration: underline'>".$year."</label></td>
                            </tr>
                            <tr>
                                <td>Course: <label style='text-decoration: underline'>".$_SESSION["classCourse"]."</label></td>
                                <td>Semester: <label style='text-decoration: underline'>".$sem."</label></td>
                            </tr>
                            <tr>
                                <td colspan='2'>Faculty: <label style='text-decoration: underline'>".$_SESSION['lastname'].", ".$_SESSION['firstname']." ".$_SESSION['middlename']."</label></td>
                            </tr>
                        </table>

                        <table class='table table-responsive table-bordered'>
                        <tr class='alert alert-info'>
                            <th></th>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Prelim &nbsp;
                                <button onclick='editPrelimGrade()' class='btn btn-xs btn-primary'><span class='glyphicon glyphicon-edit'></span>edit</button>
                                <!-- <button class='btn btn-xs btn-primary disabled'><span class='glyphicon glyphicon-save'></span>save</button> -->
                            </th>
                            <th>Midterm &nbsp;
                                <button onclick='editMidtermGrade()' class='btn btn-xs btn-primary'><span class='glyphicon glyphicon-edit'></span>edit</button>
                                <!-- <button class='btn btn-xs btn-primary disabled'><span class='glyphicon glyphicon-save'></span>save</button> -->
                            </th>
                            <th>Pre-Final &nbsp;
                                <button onclick='editPreFinalGrade()' class='btn btn-xs btn-primary'><span class='glyphicon glyphicon-edit'></span>edit</button>
                                <!-- <button class='btn btn-xs btn-primary disabled'><span class='glyphicon glyphicon-save'></span>save</button> -->
                            </th>
                            <th>Final &nbsp;
                                <button onclick='editFinalGrade()' class='btn btn-xs btn-primary'><span class='glyphicon glyphicon-edit'></span>edit</button>
                                <!-- <button class='btn btn-xs btn-primary disabled'><span class='glyphicon glyphicon-save'></span>save</button> -->
                            </th>
                        </tr>
                        ".$data."
                        <tr class='alert alert-info'>
                            <th></th>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Prelim &nbsp;
                                <button onclick='editPrelimGrade()' class='btn btn-xs btn-primary'><span class='glyphicon glyphicon-edit'></span>edit</button>
                                <!-- <button class='btn btn-xs btn-primary disabled'><span class='glyphicon glyphicon-save'></span>save</button> -->
                            </th>
                            <th>Midterm &nbsp;
                                <button onclick='editMidtermGrade()' class='btn btn-xs btn-primary'><span class='glyphicon glyphicon-edit'></span>edit</button>
                                <!-- <button class='btn btn-xs btn-primary disabled'><span class='glyphicon glyphicon-save'></span>save</button> -->
                            </th>
                            <th>Pre-Final &nbsp;
                                <button onclick='editPreFinalGrade()' class='btn btn-xs btn-primary'><span class='glyphicon glyphicon-edit'></span>edit</button>
                                <!-- <button class='btn btn-xs btn-primary disabled'><span class='glyphicon glyphicon-save'></span>save</button> -->
                            </th>
                            <th>Final &nbsp;
                                <button onclick='editFinalGrade()' class='btn btn-xs btn-primary'><span class='glyphicon glyphicon-edit'></span>edit</button>
                                <!-- <button class='btn btn-xs btn-primary disabled'><span class='glyphicon glyphicon-save'></span>save</button> -->
                            </th>
                        </tr>
                      </table>
                    </div>";
        echo $data;

        $this->closeConnection();
    }

    function savepGrade($studentId, $courseCode, $grade) {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT * FROM config;");
        $config = $sql->fetch();

        $sql1 = $this->dbHolder->prepare("UPDATE studentgrades SET pGrade = ?
                                            WHERE studentId = ?
                                            AND courseCode = ?
                                            AND sy = ?
                                            AND sem = ?;");
        $sql1->execute(array(htmlentities($grade), $studentId, $courseCode, $config[6], $config[5]));
        echo "grade=".$grade."\nstudentId=".$studentId."\ncourse=".$courseCode."\nsy=".$config[6]."\nsem=".$config[5];
        $this->closeConnection();
    }

    function savemGrade($studentId, $courseCode, $grade) {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT * FROM config;");
        $config = $sql->fetch();

        $sql1 = $this->dbHolder->prepare("UPDATE studentgrades SET mGrade = ?
                                            WHERE studentId = ?
                                            AND courseCode = ?
                                            AND sy = ?
                                            AND sem = ?;");
        $sql1->execute(array(htmlentities($grade), $studentId, $courseCode, $config[6], $config[5]));
        echo "grade=".$grade."\nstudentId=".$studentId."\ncourse=".$courseCode."\nsy=".$config[6]."\nsem=".$config[5];
        $this->closeConnection();
    }

    function savepfGrade($studentId, $courseCode, $grade) {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT * FROM config;");
        $config = $sql->fetch();

        $sql1 = $this->dbHolder->prepare("UPDATE studentgrades SET pfGrade = ?
                                            WHERE studentId = ?
                                            AND courseCode = ?
                                            AND sy = ?
                                            AND sem = ?;");
        $sql1->execute(array(htmlentities($grade), $studentId, $courseCode, $config[6], $config[5]));
        echo "grade=".$grade."\nstudentId=".$studentId."\ncourse=".$courseCode."\nsy=".$config[6]."\nsem=".$config[5];
        $this->closeConnection();
    }

    function savefGrade($studentId, $courseCode, $grade) {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT * FROM config;");
        $config = $sql->fetch();

        $sql1 = $this->dbHolder->prepare("UPDATE studentgrades SET fGrade = ?
                                            WHERE studentId = ?
                                            AND courseCode = ?
                                            AND sy = ?
                                            AND sem = ?;");
        $sql1->execute(array(htmlentities($grade), $studentId, $courseCode, $config[6], $config[5]));
        echo "grade=".$grade."\nstudentId=".$studentId."\ncourse=".$courseCode."\nsy=".$config[6]."\nsem=".$config[5];
        $this->closeConnection();
    }
}