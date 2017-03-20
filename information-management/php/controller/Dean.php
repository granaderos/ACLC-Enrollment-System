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

                $sql2 = $this->dbHolder->prepare("SELECT sectionCode, status, sectionId, type FROM sections WHERE programCode = ? AND year = ? AND semester = ? ORDER BY sectionCode;");
                $sql2->execute(array($progCode, $year[0], $sem[0]));
                $counter = 0;

                while($section = $sql2->fetch()) {
                    if($counter == 0) {
                        $data .= "<tr class='alert alert-info'><th colspan='5'>S.Y.: ".$year[0]." | Semester: ".$sem[0]."</th></tr>";
                        $data .= "<tr class='alert alert-danger'>
                                    <th>Section Code</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    <th>Type</th>
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
                                <td>".$status."</td>
                                <td>".$action."</td>
                                <td>".$section[3]." section</td>
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

            $profName = "---";
            if($prof = $sql3->fetch()) $profName = $prof[1].", ".$prof[2]." ".$prof[3];

            $enrolled = $this->getNumberOfEnrolledStudents($sectionId, $content[2]);
            $prereged = $this->getNumberOfPreregistrations($sectionId, $content[2]);
            if($enrolled == null || $enrolled == 0) $enrolled = 0;
            if($prereged == null || $prereged == 0) $prereged = 0;
            //echo "<br />sectionId = ".$sectionId."\ncourseCode = ".$content[2]."<br />";
            $data .= "<tr>
                        <td>".$content[2]."<br />".$content[6]."</td>
                        <td>".$enrolled."</td>
                        <td>".$prereged."</td>
                        <td>".$profName."</td>
                        <td><a data-toggle='modal' href='#setScheduleDiv' onclick=\"setCourseSectionSched('".$sectionId."', '".$content[2]."')\"><span class='glyphicon glyphicon-eye-open'></span>&nbsp;schedule</a> |
                        <a data-toggle='modal' href='#assignProfessorDiv' onclick=\"assignProfessor('".$sectionId."', '".$content[2]."')\"><span class='glyphicon glyphicon-edit'></span>&nbsp;instructor</a></td></tr>";
        }

        if($data == "") $data = "<tr><td>Oh... seems like there are no courses retrieved;</td></tr>";
        else $data = "<tr class='alert alert-danger'><th>Courses</th><th>Enrolled</th><th>Preregistered</th><th>Assigned Instructor</th><th>Actions</th></tr>".$data;

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

        $sql2 = $this->dbHolder->prepare("DELETE FROM preregistration WHERE studentId = ?;");
        $sql2->execute(array($_SESSION["approveStudentId"]));

        $this->closeConnection();
    }

    function displayCurriculumData() {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT * FROM config;");
        $config = $sql->fetch();

        $curSet = "<table class='table table-hover container-fluid'>
                <tr class='alert alert-danger'>
                    <th>Curriculum Year: </th>
                    <th>".$config[0]."</th>
                    <th><a><span class='glyphicon glyphicon-edit'></span> &nbsp; change</a></th>
                </tr>
                <tr class='alert alert-info'>
                    <th>Curriculum Type:</th>
                    <th>".$config[1]."</th>
                    <th><a onclick=\"promptChangeCurType('".$config[1]."')\"><span class='glyphicon glyphicon-edit'></span> &nbsp;  change</a></th>
                </tr>
                </table>";
        $curType = $config[1];
        $sql1 = $this->dbHolder->query("SELECT * FROM curriculum;");
        $curData = "";
        while($con = $sql1->fetch()) {
            $curData .= "<option>".$con[1]."</option>";
        }
        echo json_encode(array("curSet"=>$curSet, "curType"=>$curType, "curData"=>$curData));

        $this->closeConnection();
    }

    function changeCurriculumType($toChage) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("UPDATE config SET division = ?;");
        $sql->execute(array($toChage));

        $this->closeConnection();
    }

    function getNameSched($keyWord, $toSearch) {
        $this->openConnection();

        if($toSearch=="student") {
            $sql = $this->dbHolder->prepare("SELECT * FROM students
                                                WHERE lastname LIKE ?
                                                OR firstname LIKE ?
                                                OR middlename LIKE ?
                                                OR studentId LIKE ?;");
            $sql->execute(array("%".$keyWord."%", "%".$keyWord."%", "%".$keyWord."%", "%".$keyWord."%"));

            $data = "";
            while($s = $sql->fetch()) {
                $data .= "<tr>
                            <td>".$s[1]."</td>
                            <td>".$s[2].", ".$s[3]." ".$s[4]."</td>
                            <td>".$s[18]."</td>
                            <td>".$this->getOrder($s[17])." Year</td>
                            <td><a onclick=\"viewScheduleOfSI('student', ".$s[1].")\">view schedule</a></td>
                          </tr>";
            }
            if($data == "") $data = "<h3>No results for ".$keyWord.";</h3>";
            else $data = "<table class='table table-striped'>
                            <tr>
                                <th>Student ID</th>
                                <th>Full Name</th>
                                <th>Program</th>
                                <th>Year Level</th>
                                <th></th>
                            </tr>
                            ".$data."
                          </table>";
            echo $data;
        } else {
            $sql = $this->dbHolder->prepare("SELECT * FROM staff
                                                WHERE (lastName LIKE ?
                                                OR firstName LIKE ?
                                                OR middleName LIKE ?)
                                                AND type='instructor';");
            $sql->execute(array("%".$keyWord."%", "%".$keyWord."%", "%".$keyWord."%"));

            $data = "";
            while($s = $sql->fetch()) {
                $data .= "<tr>
                            <td>".$s[1].", ".$s[2]." ".$s[3]."</td>
                            <td><a onclick=\"viewScheduleOfSI('instructor', ".$s[0].")\">view schedule</a></td>
                          </tr>";
            }
            if($data == "") $data = "<h3>No results for ".$keyWord.";</h3>";
            else $data = "<table class='table table-striped'>
                            <tr>
                                <th>Full Name</th>
                                <th></th>
                            </tr>
                            ".$data."
                          </table>";
            echo $data;
        }

        $this->closeConnection();
    }

    function viewScheduleOfSI($type, $id) {
        $this->openConnection();

        $sql3 = $this->dbHolder->query("SELECT * FROM config;");
        $config = $sql3->fetch();
        if($type == "student") {
            $sql2 = $this->dbHolder->prepare("SELECT * FROM students WHERE studentId = ?;");
            $sql2->execute(array($id));
            $studInfo = $sql2->fetch();

            $sql1 = $this->dbHolder->prepare("SELECT DISTINCT sc.day FROM schedule sc, sections se, studentSchedule ss, config c, days d
                                            WHERE se.sectionId = sc.sectionId
                                            AND se.sectionId = ss.sectionId
                                            AND se.sy = c.sy
                                            AND se.year = c.year
                                            AND se.semester = c.sem
                                            AND sc.courseCode = ss.courseCode
                                            AND sc.day = d.day
                                            AND ss.studentId = ?
                                            ORDER BY d.id;");
            $sql1->execute(array($id));

            $data= "";
            while($day = $sql1->fetch()) {
                $data .= "<tr class='alert alert-info'><th colspan='3'>".$day[0]."</th></tr>";
                $sql = $this->dbHolder->prepare("SELECT sc.timeStart, sc.timeEnd, sc.room, sc.courseCode FROM schedule sc, sections se, studentSchedule ss, config c, days d
                                            WHERE se.sectionId = sc.sectionId
                                            AND se.sectionId = ss.sectionId
                                            AND se.sy = c.sy
                                            AND se.year = c.year
                                            AND se.semester = c.sem
                                            AND sc.courseCode = ss.courseCode
                                            AND sc.day = d.day
                                            and sc.day = ?
                                            AND ss.studentId = ?
                                            ORDER BY d.id, sc.timeStart, sc.timeEnd;");
                $sql->execute(array($day[0], $id));

                while($sched = $sql->fetch()) {
                    $data .= "<tr>
                            <th>".$sched[3]."</th>
                            <th>".$sched[0]." - ".$sched[1]."</th>
                            <th>".$sched[2]."</th>
                          </tr>";
                }
            }

            if($data == "") $data = "<tr><td>No schedule yet;</td></tr>";
            else $data = "
            <table class='table'>
                <tr>
                    <th>Student Name:</th>
                    <td>".$studInfo[2].", ".$studInfo[3]." ".$studInfo[4]."</td>
                    <th>School Year:</th>
                    <td>".$config[6]."</td>
                </tr>
                <tr>
                    <th>Program/Year:</th>
                    <td>".$studInfo[18]."/".$this->getOrder($studInfo[17])." Year</td>
                    <th>".$config[1].":</th>
                    <td>".$this->getOrder($config[5])." Sem</td>
                </tr>
            </table>
            <table class='table table-responsive table-striped'>
                        <tr class='alert alert-danger'>
                            <th>Course</th>
                            <th>Time</th>
                            <th>Room</th>
                        </tr>
                        ".$data."
                      </table>";
            $_SESSION["searchSchedule"] = $data;
        } else {
            $sql4 = $this->dbHolder->prepare("SELECT * FROM staff WHERE staffId = ?;");
            $sql4->execute(array($id));
            $profInfo = $sql4->fetch();

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
            $sql1->execute(array($id));

            $data= "";
            while($day = $sql1->fetch()) {
                $data .= "<tr class='alert alert-info'><th colspan='3'>".$day[0]."</th></tr>";
                $sql = $this->dbHolder->prepare("SELECT sc.timeStart, sc.timeEnd, sc.room, sc.courseCode FROM schedule sc, sections se, professorSchedule ps, config c, days d
                                            WHERE se.sectionId = sc.sectionId
                                            AND sc.sectionId = ps.sectionId
                                            AND se.sy = c.sy
                                            AND se.year = c.year
                                            AND se.semester = c.sem
                                            AND sc.courseCode = ps.courseCode
                                            AND sc.day = d.day
                                            and sc.day = ?
                                            AND ps.profId = ?
                                            ORDER BY d.id, sc.timeStart, sc.timeEnd;");
                $sql->execute(array($day[0], $id));

                while($sched = $sql->fetch()) {
                    $data .= "<tr>
                            <th>".$sched[3]."</th>
                            <th>".$sched[0]." - ".$sched[1]."</th>
                            <th>".$sched[2]."</th>
                          </tr>";
                }
            }

            if($data == "") $data = "<tr><td>No schedule yet;</td></tr>";
            else $data = "
            <table class='table'>
                <tr>
                    <th>Faculty Name:</th>
                    <td>".$profInfo[1].", ".$profInfo[2]." ".$profInfo[3]."</td>
                    <th>School Year:</th>
                    <td>".$config[6]."</td>
                </tr>
                <tr>
                    <th></th>
                    <td></td>
                    <th>".$config[1].":</th>
                    <td>".$this->getOrder($config[5])."</td>
                </tr>
            </table>
            <table class='table table-responsive table-striped'>
                        <tr class='alert alert-danger'>
                            <th>Course</th>
                            <th>Time</th>
                            <th>Room</th>
                        </tr>
                        ".$data."
                      </table>";
            $_SESSION["searchSchedule"] = $data;
        }
        $this->closeConnection();
    }

    function displayGradesEncodingStatus() {
        $this->openConnection();

        $sql = $this->dbHolder->query("SELECT pEncodingStatus, mEncodingStatus, pfEncodingStatus, fEncodingStatus FROM config;");
        $config = $sql->fetch();

        $p = "<tr>
                <td>Prelim Grades Encoding</td>
                <td>Open</td>
                <td><a class='btn btn-danger' onclick=\"toggleEncodingStatus('pEncodingStatus', 0)\"><span class='glyphicon glyphicon-lock'></span>&nbsp;LOCK</a></td>
              </tr>";
        if($config[0] == 0)
            $p = "<tr>
                <td>Prelim Grades Encoding</td>
                <td>LOCKED</td>
                <td><a class='btn btn-primary' onclick=\"toggleEncodingStatus('pEncodingStatus', 1)\"><span class='glyphicon glyphicon-edit'></span>&nbsp;OPEN</a></td>
              </tr>";
        $m = "<tr>
                <td>Midterm Grades Encoding</td>
                <td>Open</td>
                <td><a class='btn btn-danger' onclick=\"toggleEncodingStatus('mEncodingStatus', 0)\"><span class='glyphicon glyphicon-lock'></span>&nbsp;LOCK</a></td>
              </tr>";
        if($config[1] == 0)
            $m = "<tr>
                <td>Midterm Grades Encoding</td>
                <td>LOCKED</td>
                <td><a class='btn btn-primary' onclick=\"toggleEncodingStatus('mEncodingStatus', 1)\"><span class='glyphicon glyphicon-edit'></span>&nbsp;OPEN</a></td>
              </tr>";
        $pf = "<tr>
                <td>Pre-final Grades Encoding</td>
                <td>Open</td>
                <td><a class='btn btn-danger' onclick=\"toggleEncodingStatus('pfEncodingStatus', 0)\"><span class='glyphicon glyphicon-lock'></span>&nbsp;LOCK</a></td>
              </tr>";
        if($config[2] == 0)
            $pf = "<tr>
                <td>Pre-final Grades Encoding</td>
                <td>LOCKED</td>
                <td><a class='btn btn-primary' onclick=\"toggleEncodingStatus('pfEncodingStatus', 1)\"><span class='glyphicon glyphicon-edit'></span>&nbsp;OPEN</a></td>
              </tr>";
        $f = "<tr>
                <td>Final Grades Encoding</td>
                <td>Open</td>
                <td><a class='btn btn-danger' onclick=\"toggleEncodingStatus('fEncodingStatus', 0)\"><span class='glyphicon glyphicon-lock'></span>&nbsp;LOCK</a></td>
              </tr>";
        if($config[3] == 0)
            $f = "<tr>
                <td>Final Grades Encoding</td>
                <td>LOCKED</td>
                <td><a class='btn btn-primary' onclick=\"toggleEncodingStatus('fEncodingStatus', 1)\"><span class='glyphicon glyphicon-edit'></span>&nbsp;OPEN</a></td>
              </tr>";

        echo "<table class='table table-striped'>
                <tr>
                    <th>Period</th>
                    <th>Current Status</th>
                    <th>Action</th>
                </tr>
                ".$p.
                 $m.
                 $pf.
                 $f."
              </table>";



        $this->closeConnection();
    }

    function updateStatusEncoding($period, $toUpdate) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("UPDATE config SET ".$period." = ?;");
        $sql->execute(array($toUpdate));

        $this->closeConnection();
    }

    function getNumberOfEnrolledStudents($sectionId, $courseCode) {
        $sql = $this->dbHolder->prepare("SELECT DISTINCT count(ss.studentId) FROM studentSchedule ss, sections s, config c
                                            WHERE s.sectionId = ss.sectionId
                                            AND s.sy = c.sy
                                            AND s.semester = c.sem
                                            AND s.sectionId = ?
                                            AND ss.courseCode = ?;");
        $sql->execute(array($sectionId, $courseCode));
        return $sql->fetch()[0];
    }

    function getNumberOfPreregistrations($sectionId, $courseCode) {
        $sql = $this->dbHolder->prepare("SELECT count(p.studentId) FROM preregistration p, sections s, config c
                                            WHERE s.sectionId = p.sectionId
                                            AND s.sy = c.sy
                                            AND s.semester = c.sem
                                            AND s.sectionId = ?
                                            AND p.courseCode = ?
                                            GROUP BY p.studentId;");
        $sql->execute(array($sectionId, $courseCode));
        return $sql->fetch()[0];
    }

    function getOrder($num) {
        $order = array("1st", "2nd", "3rd", "4th", "5th", "6th", "7th", "8th", "9th", "10th");
        return $order[$num-1];
    }
}