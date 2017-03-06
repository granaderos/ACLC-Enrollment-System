<?php
/**
 * Created by PhpStorm.
 * User: Marejean
 * Date: 11/20/16
 * Time: 2:22 AM
 */
session_start();
include_once "DatabaseConnector.php";
class Dean extends  DatabaseConnector {

    function addSection($sectionCode, $sy, $year, $sem, $progCode, $type) {
        $this->openConnection();

        $sql1 = $this->dbHolder->prepare("SELECT * FROM sections WHERE sectionCode = ? AND sy = ? AND  year = ? AND semester = ?;");
        $sql1->execute(array($sectionCode, $sy, $year, $sem));

        if($sql1->fetch()) {
            echo "exist";
        } else {
            $sql = $this->dbHolder->prepare("INSERT INTO sections VALUES (null, ?, ?, ?, ?, ?, 0, ?);");
            $sql->execute(array($sectionCode, $progCode, $sy, $year, $sem, $type));
        }

        $this->closeConnection();
    }

    function displaySections($progCode) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT DISTINCT year FROM sections WHERE programCode = ? ORDER  by year DESC;");
        $sql->execute(array($progCode));

        $data = "";
        while($year = $sql->fetch()) {
            $sql1 = $this->dbHolder->prepare("SELECT DISTINCT semester FROM sections WHERE programCode = ? AND year = ? ORDER BY semester DESC;");
            $sql1->execute(array($progCode, $year[0]));

            while($sem = $sql1->fetch()) {

                $sql2 = $this->dbHolder->prepare("SELECT sectionCode, status, sectionId FROM sections WHERE programCode = ? AND year = ? AND semester = ? ORDER BY sectionCode;");
                $sql2->execute(array($progCode, $year[0], $sem[0]));
                $counter = 0;

                while($section = $sql2->fetch()) {
                    if($counter == 0) {
                        $data .= "<tr class='alert alert-info'><th colspan='5'>S.Y.: ".$year[0]." | Semester: ".$sem[0]."</th></tr>";
                        $data .= "<tr>
                                    <th>Section Code</th>
                                    <th># of Students Enrolled</th>
                                    <th># of Pending Registrations</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                  </tr>";
                    }

                    $status = "<label style='color: blue;'>open</label>";
                    $action = "<button class='btn btn-danger' onclick=\"closeSection('".$section[0]."', '".$year[0]."', ".$sem[0].")\">close this section</button>";
                    if($section[1] == 0) {
                        $status = "<label style='color: red'>closed</label>";
                        $action = "<button class='btn btn-primary' onclick=\"openSection('".$section[0]."', '".$year[0]."', ".$sem[0].")\">open this section</button>";
                    }

                    $data .= "<tr>
                                <td><a onclick=\"viewSchedule(".$section[2].", '".$section[0]."', '".$year[0]."', ".$sem[0].")\">".$section[0]."</a></td>
                                <td>0</td>
                                <td>0</td>
                                <td>".$status."</td>
                                <td>".$action."</td>
                              </tr>";

                    $counter++;

                }
            }

        }

        if($data == "") $data = "<p>No sections for ".$progCode." yet;You might want to add sections now.</p>";
        else $data = "<table class='table'>".$data."</table>";

        echo $data;

        $this->closeConnection();
    }

    function openSection($sectionCode, $sy, $sem) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT COUNT(*) FROM enrollmentInfo WHERE sectionCode = ? AND sy = ? AND sem = ?;");
        $sql->execute(array($sectionCode, $sy, $sem));
        $enrolled = $sql->fetch()[0];

        $sql1 = $this->dbHolder->query("SELECT sectionMAX FROM config;");
        $sectionMax = $sql1->fetch()[0];

        if($enrolled < $sectionMax) {
            $sql3 = $this->dbHolder->prepare("UPDATE sections SET status = 1 WHERE sectionCode = ? AND year = ? AND semester = ?;");
            $sql3->execute(array($sectionCode, $sy, $sem));
            echo "successful";
        } else echo "full";

        $this->closeConnection();
    }

    function closeSection($sectionCode, $sy, $sem) {
        $this->openConnection();

            $sql3 = $this->dbHolder->prepare("UPDATE sections SET status = 0 WHERE sectionCode = ? AND year = ? AND semester = ?;");
            $sql3->execute(array($sectionCode, $sy, $sem));

        $this->closeConnection();
    }

    function addSchedule($sectionId, $course, $day, $timeStart, $timeEnd, $room) {
        $this->openConnection();

        $conflict = false;
        $exist = false;

        $start = $this->getCalculableTime($timeStart);
//        $end = $this->getCalculableTime($timeEnd);

        $sql = $this->dbHolder->prepare("SELECT * FROM schedule WHERE sectionId = ? AND courseCode = ?;");
        $sql->execute(array($sectionId, $course));

        while($sec = $sql->fetch()) {
            if($sec[5] == $day) {
                if($timeStart == $sec[3] && $timeEnd == $sec[4] && $room == $sec[6]) {
                    $exist = true;
                    break;
                } else if($start >= $this->getCalculableTime($sec[3]) && $start < $this->getCalculableTime($sec[4]) && $room == $sec[6]) {
                    $conflict = true;
                    break;
                }
            }

            //echo "timeStart = ".$timeStart."\ntimeEnd = ".$timeEnd."\nroom = ".$room."\nday = ".$day."\nsec3 = ".$sec[3]."\nsec4 = ".$sec[4]."\nroom = ".$sec[6]."\ndaydb = ".$sec[5]."\n\n";

        }

        if($exist) {
            echo "exist";
        } else if($conflict) {
            echo "conflict";
        } else {
            $sql1 = $this->dbHolder->prepare("INSERT INTO schedule VALUES (null, ?, ?, ?, ?, ?, ?);");
            $sql1->execute(array($sectionId, $course, $timeStart, $timeEnd, $day, $room));
            $id = $this->dbHolder->lastInsertId();

            echo "<tr id='courseSched".$id."'>
                    <td>".$day."</td>
                    <td>".$room."</td>
                    <td>".$timeStart."</td>
                    <td>".$timeEnd."</td>
                    <td><a><span class='glyphicon glyphicon-trash' onclick='deleteSchedule(".$id.")'></span>remove</a></td>
                  </tr>";
        }

        $this->closeConnection();
    }
    
    function viewSchedule($sectionId, $sectionCode, $sy, $sem, $progCode) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT * FROM schedule WHERE sectionId = ?;");
        $sql->execute(array($sectionId));
        
        $data = "";
        while($content = $sql->fetch()) {

        }
        
        if($data == "") $data = "<h4>No schedule yet; Please set schedule now for this section.</h4>";
        
        $this->closeConnection();
    }

    function getCalculableTime($time) {
        $hour = substr($time, 0, 2);
        $half = substr($time, 3, 2);
        $ap = substr($time, 6, 2);

        if($half == "30") $hour .= .50;
        if($ap == "PM") $hour = $hour + 12;

        return $hour;
    }

    function retrieveCoursesForSchedule($sectionId) {
        $this->openConnection();

        $data = "";

        $sql = $this->dbHolder->query("SELECT curriculum, division FROM config;");
        $configData = $sql->fetch();

        $table = "coursestoprogramstrimestral";
        if($configData[1] == "Semestral")
            $table = "coursestoprogramssemestral";
        $sql1 = $this->dbHolder->prepare("SELECT * FROM sections WHERE sectionId = ?;");
        $sql1->execute(array($sectionId));
        $sectionData = $sql1->fetch();

        $sql2 = $this->dbHolder->prepare("SELECT t.*, co.description FROM ".$table." t, courses co
                                            WHERE co.courseCode = t.courseCode
                                            AND progCode = ?
                                            AND year = ?
                                            AND semester = ?
                                            AND curriculumYear = ?;");
        $sql2->execute(array($sectionData[2], $sectionData[4], $sectionData[5], $configData[0]));

        //$data .= "progCode = ".$sectionData[2]." year = ".$sectionData[4]." semester = ".$sectionData[5]." curriculum = ".$configData[0]." table = ".$table;
        //$data .= " div = ".$configData[1];
        while($content = $sql2->fetch()) {

            $sql3 = $this->dbHolder->prepare("SELECT DISTINCT st.* FROM staff st, sections se, schedule sc, professorSchedule ps, config c
                                                WHERE st.staffId = ps.profId
                                                AND se.sectionId = ps.sectionId
                                                AND se.sectionId = sc.sectionId
                                                AND sc.courseCode = ps.courseCode
                                                AND se.sectionId = ?
                                                AND sc.courseCode = ?;");
            $sql3->execute(array($sectionId, $content[2]));

            $profName = "n/a";
            if($prof = $sql3->fetch()) $profName = $prof[1].", ".$prof[2]." ".$prof[3];

            $data .= "<tr>
                        <td>".$content[2]."<br />".$content[6]."</td>
                        <td>".$profName."</td>
                        <td><a data-toggle='modal' href='#setScheduleDiv' onclick=\"setCourseSectionSched('".$sectionId."', '".$content[2]."')\">view Schedule</a> |
                        <a data-toggle='modal' href='#assignProfessorDiv' onclick=\"assignProfessor('".$sectionId."', '".$content[2]."')\">assign instructor</a></td></tr>";
        }

        if($data == "") $data = "<tr><td>Oh... seems like there are no courses retrieved;</td></tr>";
        else $data = "<tr><th>Courses</th><th>Assigned Instructor</th><th>Actions</th></tr>".$data;

        echo "<table class='table'>".$data."</table>";

        $this->closeConnection();
    }

    function retrieveProfsAndSched($sectionId, $courseCode) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT * FROM schedule WHERE sectionId = ? AND courseCode = ?;");
        $sql->execute(array($sectionId, $courseCode));

        $data = "";
        while($sched = $sql->fetch()) {
            $data .= "<tr>
                            <td>".$sched[5]."</td>
                            <td>".$sched[3]."</td>
                            <td>".$sched[4]."</td>
                            <td>".$sched[6]."</td>
                      </tr>";
        }

        /*$sql1 = $this->dbHolder->prepare("SELECT st.* FROM staff st, schedule sc,sections se, professorSchedule ps, config c
                                            WHERE se.sy = c.sy
                                            AND se.year = c.year
                                            AND se.semester = c.sem
                                            AND st.staffId = ps.profId
                                            AND st.type = 'instructor'
                                            AND st.staffId = ?;");*/

        $sql1 = $this->dbHolder->query("SELECT * FROM staff WHERE type='instructor';");

        $ins = "";
        while($pros = $sql1->fetch()) {
            $ins .= "<option value=".$pros[0].">".$pros[1].", ".$pros[2]." ".$pros[3]."</option>";
        }

        if($data == "") $data = "<h3>No schedule yet for this course;</h3>";
        else $data = "<table class='table table-responsive table-striped'><tr><th>Day</th><th>Start Time</th><th>End Time</th><th>Room</th></tr>".$data."</table>";

        echo json_encode(array("sched"=>$data, "profs"=>$ins));

        $this->closeConnection();
    }

    function assignProf($profId, $sectionId, $courseCode) {
        $this->openConnection();

        //get the new schedule to be assigned
        $sql5 = $this->dbHolder->prepare("SELECT sc.day, sc.timeStart, sc.timeEnd FROM schedule sc, sections se, config c
                                            WHERE se.sectionId = sc.sectionId
                                            AND sc.courseCode = ?
                                            AND se.sectionId = ?;");
        $sql5->execute(array($courseCode, $sectionId));

        $conflict = false;

        // get all schedule of faculty  (this is not the efficient one to do this)
        while($newSched = $sql5->fetch()) {
            if($conflict) break;
            $sql4 = $this->dbHolder->prepare("SELECT sc.day, sc.timeStart, sc.timeEnd FROM schedule sc, sections se, professorSchedule ps, config c
                                            WHERE se.sectionId = ps.sectionId
                                            AND se.sectionId = sc.sectionId
                                            AND se.sy = c.sy
                                            AND se.year = c.year
                                            AND se.semester = c.sem
                                            AND ps.profId = ?;");
            $sql4->execute(array($profId));

            while($scheds = $sql4->fetch()) {
                if($newSched[0] == $scheds[0]) {
                    if($this->getCalculableTime($newSched[1]) >= $this->getCalculableTime($scheds[1]) &&
                       $this->getCalculableTime($newSched[2]) < $this->getCalculableTime($scheds[2])) {
                        $conflict = true;
                        break;
                    }
                }
            }

        }

        if($conflict) {
            echo "conflict";
        } else {
            $sql2 = $this->dbHolder->prepare("SELECT * FROM professorSchedule WHERE sectionId = ? AND courseCode = ?;");
            $sql2->execute(array($sectionId, $courseCode));

            $done = false;

            if($prev = $sql2->fetch()) {
                $sql3 = $this->dbHolder->prepare("DELETE FROM professorSchedule WHERE psId = ?");
                $sql3->execute(array($prev[0]));
                $done = true;

            }

            $sql = $this->dbHolder->prepare("INSERT INTO professorSchedule VALUES (null,?, ?, ?);");
            $sql->execute(array($profId, $sectionId, $courseCode));

            $sql1 = $this->dbHolder->prepare("SELECT * FROM staff WHERE staffId = ?; ");
            $sql1->execute(array($profId));
            $prof = $sql1->fetch();

            if($done) echo "Previous assigned instructor is now replaced by ".$prof[1].", ".$prof[2]." ".$prof[3];
            else echo "You just assigned ".$prof[1].", ".$prof[2]." ".$prof[3]." for course ".$courseCode;
        }

        $this->closeConnection();
    }

    function viewCourseSchedule($sectionId, $course) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT s.* FROM schedule s, sections sec WHERE sec.sectionId = s.sectionId AND sec.sectionId = ? AND s.courseCode = ?;");
        $sql->execute(array($sectionId, $course));

        $data = "";
        while($content = $sql->fetch()) {
            $data .= "<tr id='courseSched".$content[0]."'>
                        <td>".$content[5]."</td>
                        <td>".$content[6]."</td>
                        <td>".$content[3]."</td>
                        <td>".$content[4]."</td>
                        <td><a onclick='deleteCourseSchedule(".$content[0].")'><span class='glyphicon glyphicon-trash'></span>&nbsp;remove</a></td>
                      </tr>";
        }

        if($data == "") $data = "<tr><td colspan='4'>No schedule yet;</td></tr>";
        echo $data;

        $this->closeConnection();
    }

    function deleteCourseSchedule($schedId) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("DELETE FROM schedule WHERE scheduleId = ?;");
        $sql->execute(array($schedId));

        $this->closeConnection();
    }

    function displayPreregStatus() {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT preregStatus FROM config;");
        $status = $sql->fetch();

        $statusLabel = "open";
        $option = "<button class='btn btn-danger btn-lg' onclick='updatePreregStatus(".$status[0].")'><span class='glyphicon glyphicon-eye-close'></span>&nbsp; close</button>";
        if($status[0] == 0) {
            $statusLabel = "close";
            $option = "<button class='btn btn-primary btn-lg' onclick='updatePreregStatus(".$status[0].")'><span class='glyphicon glyphicon-eye-open'></span>&nbsp; open</button>";
        }

        echo json_encode(array("status"=>strtoupper($statusLabel), "option"=>$option));
        $this->closeConnection();
    }

    function updatePreregStatus($topUpdate) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("UPDATE config SET preregStatus = ?;");
        $sql->execute(array($topUpdate));
        echo "to = ".$topUpdate;
        $this->closeConnection();
    }

    function displayProgramsForApprovePrereg() {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT * FROM programs;");

        $data = "";
        while($prog = $sql->fetch()) {
            $data .= "<tr><th><a onclick=\"setProgramSessionForApprovePrereg('".$prog[0]."')\">".$prog[0]." - ".$prog[1]."</a></th></tr>";
        }

        if($data == "") $data = "<h4>No programs yet;</h4>";
        else $data = "<table class='table table-hover'>".$data."</table>";
        echo $data;
        $this->closeConnection();
    }

    function getApproveDeafultData($progCode) {
        $this->openConnection();
        $_SESSION["progCodeForApproval"] = $progCode;

        $sql = $this->dbHolder->prepare("SELECT DISTINCT s.studentID ,s.lastname, s.firstname, s.middlename, s.yearLevel
                                    FROM students s, preregistration p
                                    WHERE s.studentId = p.studentId
                                    AND s.progCode = ?
                                    ORDER BY s.yearLevel, s.lastname, s.firstname, s.middlename;");
        $sql->execute(array($_SESSION["progCodeForApproval"]));

        $data = "";
        while($stud = $sql->fetch()) {
            $data .= "<tr><th><a onclick=\"showStudentPreregistration(".$stud[0].")\" data-toggle='modal' href='#showStudentRegistrationDiv'>".$stud[1].", ".$stud[2]." ".$stud[3]." (".$this->getOrder($stud[4])." Year)</a></th></tr>";
        }

        if($data == "") $data = "<h4>No pre-registrations yet;</h4>";
        else $data = "<table class='table table-hover'>
                        ".$data."
                       </table>";
        echo $data;
        $this->closeConnection();
    }

    function getFilteredPreregistrations($name, $year) {
        $this->openConnection();

        if($name != "") {
            $sql = $this->dbHolder->prepare("SELECT DISTINCT s.studentID ,s.lastname, s.firstname, s.middlename, s.yearLevel
                                        FROM students s, preregistration p
                                        WHERE s.studentId = p.studentId
                                        AND (s.lastname LIKE ?
                                        OR s.firstname LIKE ?
                                        OR s.middlename LIKE ?)
                                        AND s.progCode = ?
                                        ORDER BY s.yearLevel, s.lastname, s.firstname, s.middlename;");
            $sql->execute(array("%".$name."%", "%".$name."%", "%".$name."%", $_SESSION["progCodeForApproval"]));
        } else if($year != "") {
            $sql = $this->dbHolder->prepare("SELECT DISTINCT s.studentID ,s.lastname, s.firstname, s.middlename, s.yearLevel
                                        FROM students s, preregistration p
                                        WHERE s.studentId = p.studentId
                                        AND s.yearLevel = ?
                                        AND s.progCode = ?
                                        ORDER BY s.yearLevel, s.lastname, s.firstname, s.middlename;");

            $sql->execute(array($year, $_SESSION["progCodeForApproval"]));
        } else {
            $sql = $this->dbHolder->prepare("SELECT DISTINCT s.studentID ,s.lastname, s.firstname, s.middlename, s.yearLevel
                                        FROM students s, preregistration p
                                        WHERE s.studentId = p.studentId
                                        AND s.progCode = ?
                                        ORDER BY s.yearLevel, s.lastname, s.firstname, s.middlename;");
            $sql->execute(array($_SESSION["progCodeForApproval"]));
        }

        $data = "";
        while($stud = $sql->fetch()) {
            $data .= "<tr><th><a \"showStudentPreregistration(".$stud[0].")\" data-toggle='modal' href='#showStudentRegistrationDiv'>".$stud[1].", ".$stud[2]." ".$stud[3]." (".$this->getOrder($stud[4])." Year)</a></th></tr>";
        }

        if($data == "") $data = "<h4>No results retrieved;</h4>";
        else $data = "<table class='table table-hover'>
                        ".$data."
                       </table>";
        echo $data;
        $this->closeConnection();
    }

    function showStudentPreregistration($studentId) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT DISTINCT c.courseCode, c.description, c.units, c.labUnits, p.sectionId
                                            FROM courses c, students s, preregistration p, sections se
                                            WHERE c.courseCode = p.courseCode
                                            AND p.studentId = ?;");
        $sql->execute(array($studentId));

        $data = "";
        $sum = 0;

        while($preg = $sql->fetch()) {
            $unit = $preg[2];
            if($preg[3] > 0) $unit .= "/".$preg[3];

            $sql1 = $this->dbHolder->prepare("SELECT sectionCode FROM sections WHERE sectionId = ?;");
            $sql1->execute(array($preg[4]));
            $sectionCode = $sql1->fetch();

            $data .= "<tr>
                        <th>".$preg[0]."<br /><em>".$preg[1]."</em></th>
                        <th>".$unit."</th>
                        <th>".$sectionCode[0]."</th>
                       </tr>";
            $sum += $preg[2];
        }

        if($data == "") $data = "<h4>The system retrieved nothing;</h4>";
        else $data = "<table class='table table-hover'>
                            <tr class='alert alert-info'>
                                <th>Courses</th>
                                <th>Units</th>
                                <th>Enrolled Sections</th>
                            </tr>
                            ".$data."
                            <tr class='alert alert-danger'><th>Total Units:</th><th>".$sum."</th><th></th></tr>
                      </table>";

        $sql2 = $this->dbHolder->prepare("SELECT * FROM students WHERE studentId = ?;");
        $sql2->execute(array($studentId));
        $studInfo = $sql2->fetch();
        $_SESSION["approveStudentId"] = $studentId;
        echo json_encode(array("data"=>$data, "name"=>$studInfo[2].", ".$studInfo[3]." ".$studInfo[4]));
        $this->closeConnection();
    }

    function approveStudentPrereg() {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT * FROM preregistration WHERE studentId = ?;");
        $sql->execute(array($_SESSION["approveStudentId"]));

        while($pre = $sql->fetch()) {
            $sql1 = $this->dbHolder->prepare("INSERT INTO studentSchedule VALUES (null, ?, ?, ?);");
            $sql1->execute(array($pre[1], $pre[2], $pre[3]));
        }

        $this->closeConnection();
    }

    function getOrder($num) {
        $order = array("1st", "2nd", "3rd", "4th", "5th", "6th", "7th", "8th", "9th", "10th");
        return $order[$num-1];
    }
}