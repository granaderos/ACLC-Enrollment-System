<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 2/24/17
 * Time: 7:10 PM
 * To change this template use File | Settings | File Templates.
 */
include_once "DatabaseConnector.php";
session_start();
class Student extends DatabaseConnector {
    function login($studentId, $username, $password) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT * FROM students WHERE studentId = ? AND username = ? AND password = ?;");
        $sql->execute(array($studentId, $username, $password));

        if($data = $sql->fetch()) {
            $_SESSION["studentId"] = $data[1];
            $_SESSION["username"] = $data[22];
            $_SESSION["lastname"] = $data[2];
            $_SESSION["firstname"] = $data[3];
            $_SESSION["middlename"] = $data[4];

            echo $_SESSION["firstname"];

        } else {
            echo "invalid";
        }

        $this->closeConnection();
    }

    function displayStudentInfoAll() {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT * FROM students WHERE studentId = ?;");
        $sql->execute(array($_SESSION["studentId"]));

        while($stud = $sql->fetch()) {
            $data = array("studentId"=>$stud[1],
                           "lastname"=>$stud[2],
                           "firstname"=>$stud[3],
                           "middlename"=>$stud[4],
                           "address"=>$stud[5],
                           "emailAddress"=>$stud[6],
                           "contactNumber"=>$stud[7],
                           "nationality"=>$stud[8],
                           "birthday"=>$stud[9],
                           "gender"=>$stud[10],
                           "placeOfBirth"=>$stud[11],
                           "secondarySchool"=>$stud[12],
                           "secDateGraduated"=>$stud[13],
                           "schoolLastAttended"=>$stud[14],
                           "schoolLastAttendedDateAttended"=>$stud[15],
                           "username"=>$stud[22],
                           "gfName"=>$stud[24],
                           "gmName"=>$stud[25],
                           "glName"=>$stud[26],
                           "gRelationship"=>$stud[27],
                           "gAddress"=>$stud[28],
                           "gContactNumber"=>$stud[29],
                           );
        }

        echo json_encode($data);
        $this->closeConnection();
    }

    function saveStudentInfo($address, $emailAddress,$contactNumber, $birthday, $gender, $birthPlace,
                             $nationality, $gfName, $gmName, $glName, $gRelationship, $gAddress, $gContactNumber,
                             $sSchool,$sYearGrad, $tSchoolAttended, $tYearAttended, $username, $oldPassword, $newPassword) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT * FROM students WHERE studentId=? AND password=?;");
        $sql->execute(array($_SESSION["studentId"], $oldPassword));

        if($pass = $sql->fetch()) {
            if($newPassword != "") $oldPassword = $newPassword;
            $sql1 = $this->dbHolder->prepare("UPDATE students SET address=?, emailAddress=?, contactNumber=?, nationality=?, birthday=?,gender=?,
                                        placeOfBirth=?, secondarySchool=?, secDateGraduated=?, schoolLastAttended=?,
                                        schoolLastAttendedDateAttended=?, username=?, password=?, gfName=?, gmName=?,
                                        glName=?, gRelationship=?, gAddress=?, gContactNumber=? WHERE studentId=?;");
            $sql1->execute(array($address, $emailAddress, $contactNumber, $nationality, $birthday, $gender, $birthPlace, $sSchool,
                                    $sYearGrad, $tSchoolAttended, $tYearAttended, $username, $oldPassword, $gfName, $gmName,
                                    $glName, $gRelationship, $gAddress, $gContactNumber, $_SESSION["studentId"]));
        } else {
            echo "invalid ".$_SESSION["studentId"]."pass = ".$oldPassword;
        }

        $this->closeConnection();
    }

    function displayProfile() {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT s.firstname, s.middlename, s.lastname, p.progCode, p.description, s.yearLevel, c.sem FROM students s, programs p, config c
                                         WHERE p.progCode = s.progCode
                                         AND s.studentId = ?;");
        $sql->execute(array($_SESSION["studentId"]));

        // getting today's schedule
        $sql2 = $this->dbHolder->query("SELECT DAYOFWEEK(NOW());");
        $index = $sql2->fetch();
        $day = $this->getDayName($index[0]);

        $sql1 = $this->dbHolder->prepare("SELECT sc.day, sc.timeStart, sc.timeEnd, sc.room, sc.courseCode FROM schedule sc, sections se, studentSchedule ss, config c
                                            WHERE se.sectionId = sc.sectionId
                                            AND sc.courseCode = ss.courseCode
                                            AND se.sy = c.sy
                                            AND se.semester = c.sem
                                            AND sc.day = ?
                                            AND ss.studentId = ?
                                            ORDER BY sc.timeStart;");
        $sql1->execute(array($day, $_SESSION["studentId"]));

        $schedData = "";
        while($sched = $sql1->fetch()) {
            $schedData .= "<tr>
                        <td>".$sched[4]."</td>
                        <td>".$sched[1]." - ".$sched[2]."</td>
                        <td>".$sched[3]."</td>
                      </tr>";
        }

        if($schedData == "") {
            $schedData = "<h4>No schedule for today;</h4>";
        } else {
            $schedData = "<table class='table table-responsive table-striped'>
                        <tr class='alert alert-danger'>
                            <th class='text-center' colspan='3'>".$day."</th>
                        </tr>
                        <tr class='alert alert-info'>
                            <th>Course</th>
                            <th>Time</th>
                            <th>Room</th>
                        </tr>
                        ".$schedData."
                      </table>";
        }

        // getting currently enrolled courses
        $sql4 = $this->dbHolder->query("SELECT * FROM config;");
        $config = $sql4->fetch();

        // won't display if there is no professor assigned yet
        $sql3 = $this->dbHolder->prepare("SELECT DISTINCT c.courseCode, c.description, c.units, p.lastname, p.firstname, p.middlename
                                            FROM courses c, staff p, sections se, schedule sc, studentSchedule ss, professorSchedule ps
                                            WHERE se.sectionId = sc.sectionId
                                            AND sc.sectionId = ss.sectionId
                                            AND ss.sectionId = ps.sectionId
                                            AND p.staffId = ps.profId
                                            AND c.courseCode = ss.courseCode
                                            AND ss.courseCode = ps.courseCode
                                            AND se.sy = ?
                                            AND se.semester = ?
                                            AND ss.studentId = ?;");

        $sql3->execute(array($config[6], $config[5], $_SESSION["studentId"]));

        $coursesData = "";
        while($courses = $sql3->fetch()) {
            $coursesData .= "<tr>
                                <td>".$courses[0]."</td>
                                <td>".$courses[1]."</td>
                                <td>".$courses[2]."</td>
                                <td>".$courses[3].", ".$courses[4]." ".$courses[5]."</td>
                             </tr>";
        }

        if($coursesData == "") $coursesData = "<h4>You haven't enrolled any courses yet;</h4>";
        else $coursesData = "<table class='table table-striped'>
                                <tr class='alert alert-info'>
                                    <th>Course Code</th>
                                    <th>Course Description</th>
                                    <th>Units</th>
                                    <th>Instructor</th>
                                </tr>
                                ".$coursesData."
                                <tr class='alert alert-info'>
                                    <th>Course Code</th>
                                    <th>Course Description</th>
                                    <th>Units</th>
                                    <th>Instructor</th>
                                </tr>
                             </table>";

        $sql5 = $this->dbHolder->prepare("SELECT DISTINCT c.courseCode, c.units
                                            FROM courses c, sections se, schedule sc, students st, studentSchedule ss
                                            WHERE se.sectionId = sc.sectionId
                                            AND sc.sectionId = ss.sectionId
                                            AND st.studentId = ss.studentId
                                            AND c.courseCode = ss.courseCode
                                            AND se.sy = ?
                                            AND se.semester = ?
                                            AND st.studentId = ?;");
        $sql5->execute(array($config[6], $config[5], $_SESSION["studentId"]));
        $units = 0;

        while($unitsData = $sql5->fetch()) {
            $units += $unitsData[1];
        }

        if($stud = $sql->fetch()) {
            $data = array("firstname"=>$stud[0], "middlename"=>$stud[1], "lastname"=>$stud[2],
                "progCode"=>$stud[3],"program"=>$stud[4], "year"=>$stud[5], "sem"=>$stud[6],
                "sched"=>$schedData, "day"=>$day, "courses"=>$coursesData, "units"=>$units);
            echo json_encode($data);

        }

        $this->closeConnection();
    }

    function displayGrades() {
        $this->openConnection();

        $sql1 = $this->dbHolder->prepare("SELECT DISTINCT sy FROM studentgrades WHERE studentId = ? ORDER BY sy DESC;");
        $sql1->execute(array($_SESSION["studentId"]));

        $data = "";

        while($sy = $sql1->fetch()) {
            $sql2 = $this->dbHolder->prepare("SELECT DISTINCT sem FROM studentgrades WHERE studentId = ? AND sy = ?;");
            $sql2->execute(array($_SESSION["studentId"], $sy[0]));

            while($sem = $sql2->fetch()) {
                $sql = $this->dbHolder->prepare("SELECT DISTINCT st.studentId, co.courseCode, co.description, sg.pGrade, sg.mGrade, sg.pfGrade, sg.fGrade
                                          FROM students st, studentgrades sg, config c, courses co
                                            WHERE st.studentId = sg.studentId
                                            AND co.courseCode = sg.courseCode
                                            AND sg.sy = ?
                                            AND sg.sem = ?
                                            AND st.studentId = ?
                                            ORDER BY co.courseCode;");
                $sql->execute(array($sy[0], $sem[0], $_SESSION["studentId"]));

                $data .= "<tr class='alert alert-danger'>
                            <th class='text-center' colspan='5'>S.Y. ".$sy[0]." | ".$this->getOrder($sem[0])." SEM</th>
                          </tr>
                          <tr class='alert alert-info'>
                            <th>Course</th>
                            <th>Prelim Grade</th>
                            <th>Midterm Grade</th>
                            <th>Pre-Final Grade</th>
                            <th>Final Grade</th>
                          </tr>";

                while($grades = $sql->fetch()) {
                    $data .= "<tr>
                        <td><label>".$grades[1]."</label><br /><em>".$grades[2]."</em></td>
                        <td>".$grades[3]."</td>
                        <td>".$grades[4]."</td>
                        <td>".$grades[5]."</td>
                        <td>".$grades[6]."</td>
                      </tr>";
                }
            }

        }

        if($data == "") $data = "<h4>No records retrieved;</h4>";
        else $data = "<table class='table table-striped table-hover'>
                        ".$data."
                      </table>";
        echo $data;
        $this->closeConnection();
    }

    function displayPreregistrationData() {
        $this->openConnection();

        $sql5 = $this->dbHolder->prepare("SELECT c.courseCode, c.description, p.sectionId FROM courses c, preregistration p
                                            WHERE c.courseCode = p.courseCode
                                            AND p.studentId = ?;");
        $sql5->execute(array($_SESSION["studentId"]));

        $data = "";
        while($prereg = $sql5->fetch()) {
            $schedData = "<a class='btn btn-primary btn-lg' onclick=\"chooseSchedule('".$prereg[0]."')\" data-toggle='modal' href='#assignScheduleDiv'>Assign Schedule</a>";
            if($prereg[2] != 0) {
                // get schedule
                $sql6 = $this->dbHolder->prepare("SELECT sc.*, se.sectionCode FROM schedule sc, sections se, preregistration p, days d
                                            WHERE se.sectionId = p.sectionId
                                            AND d.day = sc.day
                                            AND sc.courseCode = p.courseCode
                                            AND se.sectionId = p.sectionId
                                            AND se.sectionId = sc.sectionId
                                            AND se.sectionId = ?
                                            AND p.courseCode = ?
                                            AND p.studentId = ?
                                            ORDER BY d.id, sc.timeStart;");
                $sql6->execute(array($prereg[2], $prereg[0], $_SESSION["studentId"]));
                $schedData = "";
                $sectionChosen = "";
                while($sched = $sql6->fetch()) {
                    $schedData .= "<tr><td>".$sched[5]."</td><td>".$sched[3]." - ".$sched[4]."</td><td>".$sched[6]."</td></tr>";
                    $sectionChosen = $sched[7];
                }
                if($schedData != "")
                    $schedData = "<table class='table table-bordered'><tr class='alert alert-danger'><th colspan='3'>Section: ".$sectionChosen."</th></tr><tr class='alert alert-info'><th>Day</th><th>Time</th><th>Room</th></tr>".$schedData."<tr class='text-center alert alert-danger'><th colspan='3'><a onclick=\"chooseSchedule('".$prereg[0]."')\" data-toggle='modal' href='#assignScheduleDiv'><span class='glyphicon glyphicon-edit'></span>&nbsp;Change Schedule</a></th></tr></table>";
            }

            $data .= "<tr>
                        <th>".$prereg[0]."<br><em>".$prereg[1]."</em></th>
                        <th>".$schedData."</th>
                      </tr>";
        }

        if($data != "") {
            $data = "<h2>Pre-registered Courses</h2>
                     <table class='table table-striped table-hover'>
                        <tr class='alert alert-info'>
                            <th>Pre-registered Courses</th>
                            <th>Schedule</th>
                        </tr>
                        ".$data."
                     </table>";
            echo json_encode(array("done"=>"true", "preregistration"=>$data));
        } else {
            $sql = $this->dbHolder->query("SELECT * FROM config;");
            $config = $sql->fetch();

            if($config[7] == 1) {
                // retrieve courses to preregister
                $table = "coursestoprogramstrimestral";
                $maxSem = 3;
                if($config[1]) {
                    $table = "coursestoprogramssemestral";
                    $maxSem = 2;

                    $sql1 = $this->dbHolder->prepare("SELECT * FROM students WHERE studentId = ?;");
                    $sql1->execute(array($_SESSION["studentId"]));
                    $studData = $sql1->fetch();
                    $curStudYear = $studData[17];
                    $sem = $maxSem+1;
                    if($config[5] >= $maxSem) {
                        $curStudYear = $curStudYear+1;
                        $sem = 1;
                    }

                    // select courses for next sem
                    $sql2 = $this->dbHolder->prepare("SELECT DISTINCT c.courseCode, c.description, c.units, c.labUnits, t.year, t.semester FROM courses c, ".$table." t, studentgrades sg
                                                    WHERE c.courseCode = t.courseCode
                                                    AND sg.fGrade != 'D' AND sg.fGrade <= 3 AND sg.fGrade > 0
                                                    AND progCode = ?
                                                    AND sg.studentId = ?
                                                    ORDER BY t.year, t.semester;");
                    $sql2->execute(array($studData[18], $_SESSION["studentId"])); // put program here Mj; last thing before you sleep

                    while($nextC = $sql2->fetch()) {
                        $sql3 = $this->dbHolder->prepare("SELECT preReqCourse FROM prerequisites WHERE courseCode = ?;");
                        $sql3->execute(array($nextC[0]));

                        $okay = true;
                        while($preq = $sql3->fetch()) {
                            //check grades if passed
                            $sql4 = $this->dbHolder->prepare("SELECT DISTINCT sg.fGrade
                                                          FROM students st, studentgrades sg, config c, courses co
                                                          WHERE st.studentId = sg.studentId
                                                            AND co.courseCode = sg.courseCode
                                                            AND sg.courseCode = ?
                                                            AND st.studentId = ?
                                                          ORDER BY sg.sy DESC, sg.sem DESC
                                                          LIMIT 1;");
                            $sql4->execute(array($preq[0], $_SESSION["studentId"]));

                            if($preqGrade = $sql4->fetch()) {
                                if($preqGrade[0] == "D") {
                                    $okay = false;
                                    break;
                                } else if($preqGrade[0] <= 0 && $preqGrade[0] > 3) {
                                    $okay = false;
                                    break;
                                }
                            }
                        }
                        if($okay) {
                            // display course for prereq
                            $unit = $nextC[2];
                            if($nextC[3] > 0) $unit .= "/".$nextC[3];
                            $data .= "<tr>
                                    <td><input type='checkbox' id='check".$nextC[0]."' onclick=\"selectCourse('".$nextC[0]."', $nextC[2])\" />
                                        <label>".$nextC[0]." - ".$nextC[1]."</label>
                                    </td>
                                    <td>".$unit."</td>
                                    <td>".$this->getOrder($nextC[4])." Year / ".$this->getOrder($nextC[5])." ".$config[1]."</td>
                                 </tr>";
                        }
                    }
                    if($data == "") $data = "<h4>Nothing to pre-register;</h4>";
                    else $data = "<table class='table table-hover table-striped'>
                                <tr class='alert alert-info'>
                                    <th>Courses to Pre-register</th>
                                    <th>Units</th>
                                    <th>To Take</th>
                                </tr>
                                ".$data."
                              </table>";
                    echo json_encode(array("done"=>"no", "preregistration"=>$data));
                }
            } else {
                echo json_encode(array("done"=>"notTime", "preregistration"=>"<h4>Not time yet for pre-registration;</h4>"));
            }
        }

        $this->closeConnection();
    }

    function savePreregistration($prereg) {
        $this->openConnection();


        for($i = 0; $i < sizeof($prereg); $i++) {
            $sql = $this->dbHolder->prepare("INSERT INTO preregistration VALUES (null, ?, ?, 0);");
            $sql->execute(array($_SESSION["studentId"], $prereg[$i]));
        }

        $this->closeConnection();
    }

    function chooseSchedule($courseCode) {
        $this->openConnection();

        $sql1 =  $this->dbHolder->prepare("SELECT DISTINCT se.sectionId, se.sectionCode FROM sections se, schedule sc, config c
                                            WHERE se.sectionId = sc.sectionId
                                            AND se.sy = c.sy
                                            AND se.semester = c.sem
                                            AND sc.courseCode = ?
                                          ORDER BY se.sectionCode;");
        $sql1->execute(array($courseCode));

        $data = "";
        while($sec = $sql1->fetch()) {

            $sql = $this->dbHolder->prepare("SELECT sc.* FROM schedule sc, sections se, days d
                                            WHERE se.sectionId = sc.sectionId
                                            AND d.day = sc.day
                                            AND se.sectionId = ?
                                            AND sc.courseCode = ?
                                          ORDER BY d.id, sc.timeStart;");
            $sql->execute(array($sec[0], $courseCode));
//            echo "sec = ".$sec[0]." course=".$courseCode;
            $counter = 0;
            $has = false;
            $schedData = "";
            while($sched = $sql->fetch()) {
                if($counter == 0) {
                    $has = true;
                    $data .= "<tr><th><label style='text-decoration: underline'>".$sec[1]."</label><br/>";
                }
                $schedData .= "<tr><td>".$sched[5]."</td><td>".$sched[3]." - ".$sched[4]."</td><td>".$sched[6]."</td></tr>";
                $counter++;
            }
            if($schedData != "") $schedData = "<table class='table table-bordered'><tr><th>Day</th><th>Time</th><th>Room</th></tr>".$schedData."</table>";
            if($has) $data .= $schedData."</th><th><a onclick=\"saveChosenSection('".$courseCode."', ".$sec[0].")\"><span class='glyphicon glyphicon-thumbs-up'></span>&nbsp;&nbsp;Choose This</a></th></tr>";
        }
        if($data == "") $data = "<h4>No available sections;</h4>";
        else $data = "<table class='table table-striped table-hover'><tr class='alert alert-info'><th>Available Sections</th><th></th></tr>".$data."</table>";

        echo $data;
        $this->closeConnection();
    }

    function saveChosenSection($courseCode, $sectionId) {
        $this->openConnection();

        // determin first if student already chose before
        $sql = $this->dbHolder->prepare("SELECT sectionId FROM preregistration WHERE studentId = ? AND courseCode = ?;");
        $sql->execute(array($_SESSION["studentId"], $courseCode));
        $prevSec = $sql->fetch();

        $message = "";
        if($prevSec[0] > 0) {
            // meanig, will overrired the previous chosen section
            $message = "Previous chosen section will be overriden!\n\nYou successfully assigned new schedule to ".$courseCode.".";

        }

        //get the new chosen schedule
        $sql5 = $this->dbHolder->prepare("SELECT sc.day, sc.timeStart, sc.timeEnd p.courseCode FROM schedule sc, sections se, preregistration p
                                            WHERE se.sectionId = sc.sectionId
                                            AND sc.courseCode = p.courseCode
                                            AND sc.courseCode = ?
                                            AND se.sectionId = ?;");
        $sql5->execute(array($courseCode, $sectionId));

        $conflict = false;
        $courseConflict = "";

        // get all previous chosen schedule of student
        while($newSched = $sql5->fetch()) {
            if($conflict) break;
            $sql4 = $this->dbHolder->prepare("SELECT sc.day, sc.timeStart, sc.timeEnd, p.courseCode FROM schedule sc, sections se, preregistration p
                                            WHERE se.sectionId = p.sectionId
                                            AND se.sectionId = sc.sectionId
                                            AND p.studentId = ?;");
            $sql4->execute(array($_SESSION["studentId"]));

            while($scheds = $sql4->fetch()) {
                if($newSched[0] == $scheds[0]) {
                    if($this->getCalculableTime($newSched[1]) >= $this->getCalculableTime($scheds[1]) &&
                        $this->getCalculableTime($newSched[2]) < $this->getCalculableTime($scheds[2])) {
                        $conflict = true;
                        $courseConflict = $scheds[3];
                        break;
                    }
                }
            }
        }

        if($conflict) {
            $message = "Conflict detected!\n\nConflict with the course ".$courseConflict.".";
            echo json_encode(array("conflict"=>"true", "message"=>$message));
        } else {
            $sql1 = $this->dbHolder->prepare("UPDATE preregistration SET sectionId = ? WHERE studentId = ? AND courseCode = ?;");
            $sql1->execute(array($sectionId, $_SESSION["studentId"], $courseCode));
            if($message == "") $message = "You successfully assigned schedule to ".$courseCode.".";
            echo json_encode(array("conflict"=>"false", "message"=>$message));
        }

        $this->closeConnection();
    }

    function getDayName($index) {
        $days = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
        return $days[$index-1];
    }

    function getOrder($num) {
        $order = array("1st", "2nd", "3rd", "4th", "5th", "6th", "7th", "8th", "9th", "10th");
        return $order[$num-1];
    }
}