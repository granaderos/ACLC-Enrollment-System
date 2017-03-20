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

    function getStudentSchedule($studentId) {
        $sql1 = $this->dbHolder->prepare("SELECT DISTINCT sc.day FROM schedule sc, sections se, studentSchedule ss, config c, days d
                                            WHERE se.sectionId = sc.sectionId
                                            AND se.sectionId = ss.sectionId
                                            AND se.sy = c.sy
                                            AND se.year = c.year
                                            AND se.semester = c.sem
                                            AND sc.courseCode = ps.courseCode
                                            AND sc.day = d.day
                                            AND ss.studentId = ?
                                            ORDER BY d.id;");
        $sql1->execute(array($studentId));

        $data= "";
        while($day = $sql1->fetch()) {
            $data .= "<tr class='alert alert-info'><th colspan='3'>".$day[0]."</th></tr>";
            $sql = $this->dbHolder->prepare("SELECT sc.timeStart, sc.timeEnd, sc.room, sc.courseCode FROM schedule sc, sections se, studentSchedule ss, config c, days d
                                            WHERE se.sectionId = sc.sectionId
                                            AND se.sectionId = ps.sectionId
                                            AND se.sy = c.sy
                                            AND se.year = c.year
                                            AND se.semester = c.sem
                                            AND sc.courseCode = ss.courseCode
                                            AND sc.day = d.day
                                            and sc.day = ?
                                            AND ss.studentId = ?
                                            ORDER BY d.id, sc.timeStart;");
            $sql->execute(array($day[0], $studentId));

            while($sched = $sql->fetch()) {
                $data .= "<tr>
                            <th>".$sched[3]."</th>
                            <th>".$sched[0]." - ".$sched[1]."</th>
                            <th>".$sched[2]."</th>
                          </tr>";
            }
        }

        if($data == "") $data = "<table class='table'><tr><td>No schedule yet;</td></tr></table>";
        else $data = "<table class='table table-responsive table-striped'>
                        <tr>
                            <th>Course</th>
                            <th>Time</th>
                            <th>Room</th>
                        </tr>
                        ".$data."
                      </table>";
        return $data;
    }

    function enrollStudent($firstName, $middleName, $lastName, $id, $program, $curriculum, $year, $type, $reqsIds, $section) {
        $this->openConnection();

        $sql5 = $this->dbHolder->prepare("SELECT * FROM students WHERE firstname = ? AND middlename = ? AND lastname = ?;");
        $sql5->execute(array($firstName, $middleName, $lastName));

        if($sql5->fetch()) {
            echo "exist";
        } else {
            $username = str_replace(" ", "_", $lastName).substr($firstName, 0, 1).$middleName[strlen($middleName)-1];

            $sql = $this->dbHolder->prepare("INSERT INTO students VALUES(null, ?, ?, ?, ?, null, null, null, null, null, null,
                  null, null, null, null, null, null, ?, ?, ?, ?, ?, ?, password(?), null, null, null, null, null, null, -1);");
            $sql->execute(array($id, $lastName, $firstName, $middleName, $year, $program, $curriculum,
                $this->getCurrentDate(), $type, $username, $username));

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

            $sql4 = $this->dbHolder->prepare("SELECT c.courseCode, c.units FROM ".$table." t, courses c
                                            WHERE c.courseCode = t.courseCode
                                            AND t.progCode = ?
                                            AND curriculumYear = ?
                                            AND t.year = ?
                                            AND t.semester = 1;");
            $sql4->execute(array($program, $config[0], $year));

            $totalUnits = 0;
            while($courses = $sql4->fetch()) {
                $totalUnits += $courses[1];

                //add block schedule
                $sql2 = $this->dbHolder->prepare("INSERT INTO studentSchedule VALUES (null, ?, ?, ?);");
                $sql2->execute(array($id, $courses[0], $section));

                // add into studentgrades table
                $sql6 = $this->dbHolder->prepare("INSERT INTO studentgrades VALUES (?, ?, ?, ?, ?, 0, 0, 0, 0, null);");
                $sql6->execute(array($id, $courses[0], $config[6], $year, $config[5]));
            }

            //compute tuition here
            $sql6 = $this->dbHolder->query("SELECT feePerUnit FROM tuitionFee;");
            $feePerUnit = $sql6->fetch()[0];

            $tuitionFee = $totalUnits*$feePerUnit;

            // get miscellaneous fees
            $sql7 = $this->dbHolder->prepare("SELECT * FROM miscellaneous WHERE toWhom = 'all' OR toWhom = ?;");
            $sql7->execute(array($program));

            $miscTotal = 0;
            while($misc = $sql7->fetch()) {
                $miscTotal += $misc[2];
            }

            $totalFee = $tuitionFee+$miscTotal;

            $sql8 = $this->dbHolder->prepare("INSERT INTO studentBalance VALUES (null, ?, ?, ?, ?, ?);");
            $sql8->execute(array($id, $totalFee, $totalFee, $config[6], $config[5]));

            $sql9 = $this->dbHolder->prepare("INSERT INTO studentPhoto VALUES (null, ?, ?);");
            $sql9->execute(array($id, "mj.png"));

            $this->closeConnection();

            echo "New students was successfully enrolled! Official Registration form will be printable as the student pay for the down payment at least;";
        }
    }

    function showRegistrationForm($studentId) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT * FROM students WHERE studentId = ?;");
        $sql->execute(array($studentId));
        $studInfo = $sql->fetch();

        //select curriculum first
        $sql3 = $this->dbHolder->query("SELECT * FROM config;");
        $config = $sql3->fetch();
        $table = "coursestoprogramstrimestral";
        if($config[1] == "Semestral") $table = "coursestoprogramssemestral";

        $sql4 = $this->dbHolder->prepare("SELECT c.courseCode, c.units FROM ".$table." t, courses c
                                            WHERE c.courseCode = t.courseCode
                                            AND t.progCode = ?
                                            AND curriculumYear = ?
                                            AND t.year = ?
                                            AND t.semester = ?;");
        $sql4->execute(array($studInfo[18], $config[0], $studInfo[17], $config[5]));
        $l = "prog=".$studInfo[18]." cur=".$config[0]." stud-year=".$studInfo[17]." sem=".$config[5];
        $totalUnits = 0;
        while($courses = $sql4->fetch()) {
            $totalUnits += $courses[1];
        }

        //compute tuition here

        $sql6 = $this->dbHolder->query("SELECT feePerUnit FROM tuitionFee;");
        $feePerUnit = $sql6->fetch()[0];

        $tuitionFee = $totalUnits*$feePerUnit;

        // get miscellaneous fees
        $sql7 = $this->dbHolder->prepare("SELECT * FROM miscellaneous WHERE toWhom = 'all' OR toWhom = ?;");
        $sql7->execute(array($studInfo[18]));

        $miscTotal = 0;
        $fees = "<label>Tuition Fee: </label>&nbsp;".$feePerUnit." x ".$totalUnits." = ".$tuitionFee."<br />
                <span style='text-decoration: underline;'>MISCELLANEOUS</span><br />";
        while($misc = $sql7->fetch()) {
            $miscTotal += $misc[2];
            $fees .= "<label>".$misc[1].":&nbsp;</label>".$misc[2]."<br />";
        }

        $totalFee = $tuitionFee+$miscTotal;
        $downPayment = $totalFee*.3;
        $pLessD = $totalFee-$downPayment;
        $pBreak = $pLessD/4;

        $feeBreakdown =
            "<label>Total Fee: &nbsp;</label>".$totalFee."<br />
                <span style='text-decoration: underline;'>PAYMENT BREAKDOWN</span><br />
                <label>Downpayment: &nbsp;</label>".$downPayment."<br />
                <label>Prelim: &nbsp;</label>".$pBreak."<br />
                <label>Midterm: &nbsp;</label>".$pBreak."<br />
                <label>Prefinal:&nbsp; </label>".$pBreak."<br />
                <label>Final:&nbsp; </label>".$pBreak."<br />
            </ul>";

        $spCredential =
            "<label>STUDENT PORTAL<br />(Default Credentials)</label><br />
            <label>Username: </label> ".$studInfo[22]."<br />
            <label>Password: </label> ".$studInfo[22];

        $sql8 = $this->dbHolder->prepare("INSERT INTO studentBalance VALUES (null, ?, ?, ?);");
        $sql8->execute(array($studentId, $totalFee, $totalFee));

        $sql9 = $this->dbHolder->prepare("SELECT description FROM programs WHERE progCode = ?;");
        $sql9->execute(array($studInfo[18]));
        $progDescription = $sql9->fetch()[0];
        $curType = "Trimesral";
        if($config[1] == "Semestral") $curType = "Semester";
        $regForm =
            "<div>
                    <h2 class='text-center'>ACLC College of Gapan</h2>
                     <p class='text-center'>
                        <label class='text-center'>School Year ".$config[6]."</label><br />
                        <label class='text-center'>".$this->getOrder($config[5])." ".$curType."</label>
                    </p>
                    <h4 class='text-center'>STUDENT REGISTRATION FORM</h4>
                <table class='table'>
                    <tr>
                        <th class='text-right'>Student ID:</th>
                        <td class='text-left'>".$studentId."</td>
                        <th class='text-right'>Program:</th>
                        <td class='text-left'>".$studInfo[18]." - ".$progDescription."</td>
                    </tr>
                    <tr>
                        <th class='text-right'>Name:</th>
                        <td class='text-left'>".$studInfo[2].", ".$studInfo[3]." ".$studInfo[4]."</td>
                        <th class='text-right'>Curriculum:</th>
                        <td class='text-left'>".$config[0]."</td>
                    </tr>
                </table>
                ".$this->getStudentSchedule($studentId)."
                <div class='row'>
                    <div class='col-lg-4'>
                        ".$fees."
                    </div>

                    <div class='col-lg-4'>
                        ".$feeBreakdown."
                    </div>

                    <div class='col-lg-4'>
                        ".$spCredential."
                    </div>
                </div>
                <table class='table'><tr><th>Date Enrolled: ".$studInfo[20]."</th></tr></table>
            </div>";

        $_SESSION["regForm"] = $regForm;
        $this->closeConnection();
    }

    function getOrder($num) {
        $order = array("1st", "2nd", "3rd", "4th", "5th", "6th", "7th", "8th", "9th", "10th");
        return $order[$num-1];
    }

    function getRecentlyEnrolledStudents($name, $program, $year) {
        $this->openConnection();

        if($name != "") {
            $sql = $this->dbHolder->prepare("SELECT * FROM students WHERE lastname LIKE ? OR firstname LIKE ? OR middlename LIKE ? ORDER BY progCode, yearLevel, lastname, firstname;");
            $sql->execute(array("%".$name."%", "%".$name."%", "%".$name."%"));
            $data = "";
            while($content = $sql->fetch()) {
                // check if student has at least paid for the current semester
                $sql1 = $this->dbHolder->prepare("SELECT t.* FROM transactions t, config c
                                                    WHERE t.sy = c.sy
                                                    AND t.sem = c.sem
                                                    AND (t.paymentFor = 'downPayment'
                                                    OR t.paymentFor = 'fullPayment')
                                                    AND t.studentId = ?;");
                $sql1->execute(array($content[1]));

                $regBut = "<label class='label label-danger'>Registration Form will only be available once the student pay for at least the downpayment;</label>";
                if($sql1->fetch()) {
                    $regBut = "<button class='btn btn-xs btn-primary' onclick='showRegistrationForm(".$content[1].")'>Registration Form</button>";
                }

                $data .=
                    "<tr>
                        <td>".$content[1]."</td>
                        <td><a onclick='setStudentSession(".$content[1].")'>".$content[2].", ".$content[3]." ".$content[4]."</a></td>
                        <td>".$content[18]."</td>
                        <td>".$content[17]."</td>
                        <td>".$regBut."</td>
                    </tr>";
            }

            if($data == "") $data = "No record found;";
            else $data = "<h4>Result/s for ".$name."</h4>
                            <table class='table table-striped'>
                                <tr class='alert alert-danger'><th>Student #</th><th>Full Name</th><th>Program</th><th>Year Level</th><th></th></tr>
                                ".$data."
                            </table>";
            echo $data;
        } else if($program != "") {
                if($year != "") {
                    $sql = $this->dbHolder->prepare("SELECT * FROM students WHERE progCode = ? AND yearLevel = ? ORDER BY lastname, firstname, middlename;");
                    $sql->execute(array($program, $year));

                    $title = "<h4><label>".$program." Students (".$this->getOrder($year)." Year)</label></h4>";
                } else {
                    $sql = $this->dbHolder->prepare("SELECT * FROM students WHERE progCode = ? ORDER BY yearLevel, lastname, firstname, middlename;");
                    $sql->execute(array($program));

                    $title = "<h4><label>".$program." Students</label></h4>";

                }

                $data = "";
                while($content = $sql->fetch()) {
                    // check if student has at least paid for the current semester
                    $sql1 = $this->dbHolder->prepare("SELECT t.* FROM transactions t, config c
                                                    WHERE t.sy = c.sy
                                                    AND t.sem = c.sem
                                                    AND (t.paymentFor = 'downPayment'
                                                    OR t.paymentFor = 'fullPayment')
                                                    AND t.studentId = ?;");
                    $sql1->execute(array($content[1]));

                    $regBut = "<label class='label label-danger'>Registration Form will only be available once the student pay for at least the downpayment;</label>";
                    if($sql1->fetch()) {
                        $regBut = "<button class='btn btn-xs btn-primary' onclick='showRegistrationForm(".$content[1].")'>Registration Form</button>";
                    }

                    $data .=
                        "<tr>
                            <td>".$content[1]."</td>
                            <td><a onclick='setStudentSession(".$content[1].")'>".$content[2].", ".$content[3]." ".$content[4]."</a></td>
                            <td>".$content[18]."</td>
                            <td>".$content[17]."</td>
                            <td>".$regBut."</td>
                        </tr>";
                }

                if($data == "") $data = "No record found;";
                else $data = $title."<table class='table table-striped'>
                                        <tr class='alert alert-danger'><th>Student #</th><th>Full Name</th><th>Program</th><th>Year Level</th><th></th></tr>
                                        ".$data."
                                     </table>";
                echo $data;
        } else if($year != "") {
            $sql = $this->dbHolder->prepare("SELECT * FROM students WHERE yearLevel = ? ORDER BY progCode, lastname, firstname, middlename DESC limit 10;");
            $sql->execute(array($year));

            $data = "";
            while($content = $sql->fetch()) {
                // check if student has at least paid for the current semester
                $sql1 = $this->dbHolder->prepare("SELECT t.* FROM transactions t, config c
                                                    WHERE t.sy = c.sy
                                                    AND t.sem = c.sem
                                                    AND (t.paymentFor = 'downPayment'
                                                    OR t.paymentFor = 'fullPayment')
                                                    AND t.studentId = ?;");
                $sql1->execute(array($content[1]));

                $regBut = "<label class='label label-danger'>Registration Form will only be available once the student pay for at least the downpayment;</label>";
                if($sql1->fetch()) {
                    $regBut = "<button class='btn btn-xs btn-primary' onclick='showRegistrationForm(".$content[1].")'>Registration Form</button>";
                }

                $data .=
                    "<tr>
                        <td>".$content[1]."</td>
                        <td><a onclick='setStudentSession(".$content[1].")'>".$content[2].", ".$content[3]." ".$content[4]."</a></td>
                        <td>".$content[18]."</td>
                        <td>".$content[17]."</td>
                        <td>".$regBut."</td>
                    </tr>";
            }


            if($data == "") $data = "No record found;";
            else $data = "<h4><label>".$this->getOrder($year)." Year Students</label></h4>
                            <table class='table table-striped'>
                                <tr class='alert alert-danger'><th>Student #</th><th>Full Name</th><th>Program</th><th>Year Level</th><th></th></tr>
                                ".$data."
                            </table>";
            echo $data;
        } else {
            $sql = $this->dbHolder->query("SELECT * FROM students ORDER BY id DESC limit 10;");

            $data = "";
            while($content = $sql->fetch()) {
                // check if student has at least paid for the current semester
                $sql1 = $this->dbHolder->prepare("SELECT t.* FROM transactions t, config c
                                                    WHERE t.sy = c.sy
                                                    AND t.sem = c.sem
                                                    AND (t.paymentFor = 'downPayment'
                                                    OR t.paymentFor = 'fullPayment')
                                                    AND t.studentId = ?;");
                $sql1->execute(array($content[1]));

                $regBut = "<label class='label label-danger'>Registration Form will only be available once the student pay for at least the downpayment;</label>";
                if($sql1->fetch()) {
                    $regBut = "<button class='btn btn-xs btn-primary' onclick='showRegistrationForm(".$content[1].")'>Registration Form</button>";
                }

                $data .=
                    "<tr>
                        <td>".$content[1]."</td>
                        <td><a onclick='setStudentSession(".$content[1].")'>".$content[2].", ".$content[3]." ".$content[4]."</a></td>
                        <td>".$content[18]."</td>
                        <td>".$content[17]."</td>
                        <td>".$regBut."</td>
                    </tr>";
            }


            if($data == "") $data = "No record found;";
            else $data = "<h4><label>Recently Enrolled Students</label></h4>
                            <table class='table table-striped'>
                                <tr class='alert alert-danger'><th>Student #</th><th>Full Name</th><th>Program</th><th>Year Level</th><th></th></tr>
                                ".$data."
                            </table>";
            echo $data;
        }
        $this->closeConnection();
    }

    function getStudentInfo() {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("SELECT s.*, p.*, sp.photoName FROM students s, programs p, studentPhoto sp
                                         WHERE s.progCode = p.progCode
                                         AND s.studentId = sp.studentId
                                         AND s.studentId = ?;");
        $sql->execute(array($_SESSION["regStudentId"]));

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
                           "program"                       =>$content[32],
                           "photo"                         =>$content[33],
                           "age"                           =>$age
            );
        }
        echo json_encode($data);

        $this->closeConnection();
    }

    function displaySubmittedReqs() {
        $this->openConnection();

        $sql1 = $this->dbHolder->prepare("SELECT r.* FROM requirements r, students s
                                            WHERE (s.type = r.requiree
                                            OR r.requiree = 'students')
                                            AND s.studentId = ?;");
        $sql1->execute(array($_SESSION["regStudentId"]));

        $data = "";
        while($req = $sql1->fetch()) {
            $sql = $this->dbHolder->prepare("SELECT * FROM studentrequirements
                                            WHERE reqId = ?
                                            AND studentId = ?;");
            $sql->execute(array($req[0], $_SESSION["regStudentId"]));

            if($sr = $sql->fetch()) {
                $fileStat = "<a data-toggle='modal' href='#promptUploadReqDiv' class='btn btn-danger' onclick='promptUploadReq(".$_SESSION["regStudentId"].", ".$req[0].")'>upload scanned ".$req[1]."</a>";
                if($sr[3] != NULL) {
                    $fileStat = "<a class='btn btn-primary' onclick=\"viewReqFile('".$sr[3]."')\" data-toggle='modal' href='#viewReqImage' >view ".$req[1]."</a>";
                }

                $data .= "<tr>
                            <th>".$req[1]."</th>
                            <th><span class='glyphicon glyphicon-thumbs-up'></span>&nbsp;already submitted</th>
                            <th>".$sr[4]."</th>
                            <th>".$fileStat."</th>
                          </tr>";
            } else {
                $data .= "<tr>
                            <th>".$req[1]."</th>
                            <th><button class='btn btn-danger' onclick='submitRequirement(".$_SESSION["regStudentId"].", ".$req[0].")'>not yet submitted (click to change status)</button></th>
                            <th>---</th>
                            <th></th>
                          </tr>";
            }
        }

        if($data == "") $data = "<h2>No records retrieved;</h2>";
        else $data = "<table class='table table-striped table-hover'>
                        <tr class='alert alert-info'>
                            <th>Requirement Description</th>
                            <th>Status</th>
                            <th>Date Submitted</th>
                            <th></th>
                        </tr>
                        ".$data."
                      </table>";
        echo $data;
        $this->closeConnection();
    }

    function submitRequirement($studentId, $reqId) {
        $this->openConnection();

        $sql = $this->dbHolder->prepare("INSERT INTO studentrequirements VALUES (?, ?, 1, null, ?);");
        $sql->execute(array($studentId, $reqId, $this->getCurrentDate()));

        $this->closeConnection();
    }

    function uploadReqImage($studentId, $reqId, $uniquePhotoName) {
        if($studentId == "none") echo "Unable to upload the file you have chosen;";
        else {
            $this->openConnection();

            $sql = $this->dbHolder->prepare("UPDATE studentrequirements SET file = ? WHERE studentId = ? AND reqId = ?;");
            $sql->execute(array($uniquePhotoName, $studentId, $reqId));

            $this->closeConnection();
        }
    }

    function uploadStudImage($photoName) {
        if($photoName == "none") echo "Unable to upload the file you have chosen;";
        else {
            $this->openConnection();

            $sql = $this->dbHolder->prepare("UPDATE studentPhoto SET photoName = ? WHERE studentId = ?;");
            $sql->execute(array($photoName, $_SESSION["regStudentId"]));

            echo $photoName;
            $this->closeConnection();
        }
    }

    function generateTOR($studentId) {
        $this->openConnection();

        $sql1 = $this->dbHolder->prepare("SELECT DISTINCT sy FROM studentgrades WHERE studentId = ? ORDER BY sy DESC;");
        $sql1->execute(array($studentId));

        $data = "";

        $sql3 = $this->dbHolder->prepare("SELECT * FROM students WHERE studentId = ?;");
        $sql3->execute(array($studentId));
        if($stud = $sql3->fetch()) {
            $sql4 = $this->dbHolder->prepare("SELECT description FROM programs WHERE progCode = ?;");
            $sql4->execute(array($stud[18]));
            $progDesc = $sql4->fetch()[0];


            $data .= "<div class='text-center'>
                                <h1 class='text-center'>ACLC COLLEGE OF GAPAN CITY</h1>
                                <h5>GAPAN CITY</h5>
                                <h4>Office of the Registrar</h4>
                                <h2 class='text-center'>Official Transcript of Record</h2>
                              </div>

                              <table class='table'>
                                <tr>
                                    <td>NAME:</td>
                                    <th>".strtoupper($stud[2]).", ".strtoupper($stud[3])." ".strtoupper($stud[4])."</th>
                                    <td>USN:</td>
                                    <th>".$studentId."</th>
                                    <td>CAMPUS:</td>
                                    <th>ACLC COLLEGE OF GAPAN</th>
                                </tr>
                                <tr>
                                    <th class='text-center' colspan='6'>PERSONAL DATA</th>
                                </tr>
                                <tr>
                                    <td>Nationality</td>
                                    <td>:</td>
                                    <td>".$stud[8]."</td>
                                    <td>Term/ SY Admitted</td>
                                    <td>:</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Date of Birth</td>
                                    <td>:</td>
                                    <td>".$stud[9]."</td>
                                    <td>Place of Birth</td>
                                    <td>:</td>
                                    <td>".$stud[11]."</td>
                                </tr>
                                <tr>
                                    <td> Gender</td>
                                    <td>:</td>
                                    <td>".$stud[10]."</td>
                                    <td>Address</td>
                                    <td>:</td>
                                    <td>".$stud[5]."</td>
                                </tr>
                                <tr>
                                    <th class='text-center' colspan='6'>SCHOLASTIC DATA</th>
                                </tr>
                                <tr>
                                    <td>Secondary School</td>
                                    <td>:</td>
                                    <td>".$stud[12]."</td>
                                    <td>Date Graduated</td>
                                    <td>:</td>
                                    <td>".$stud[13]."</td>
                                </tr>
                                <tr>
                                    <td>School Last Attended</td>
                                    <td>:</td>
                                    <td>".$stud[14]."</td>
                                    <td>Date Attended</td>
                                    <td>:</td>
                                    <td>".$stud[15]."</td>
                                </tr>
                                <tr>
                                    <td>Program Completed</td>
                                    <td>:</td>
                                    <td>".$progDesc."</td>
                                    <td>Date Completed</td>
                                    <td>:</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan='6'></td>
                                </tr>
                              </table>
                              <table class='table table-striped table-hover'>
                                <tr class='alert alert-danger'>
                                    <td colspan='4'>Beginning of Undergraduate Record</td>
                                </tr>
                              ";
        }
        while($sy = $sql1->fetch()) {
            $sql2 = $this->dbHolder->prepare("SELECT DISTINCT sem FROM studentgrades WHERE studentId = ? AND sy = ?;");
            $sql2->execute(array($studentId, $sy[0]));

            while($sem = $sql2->fetch()) {
                $data .= "<tr class='alert alert-danger'>
                                <th class='text-center' colspan='4'>S.Y. ".$sy[0]." | ".$this->getOrder($sem[0])." SEM</th>
                              </tr>
                              <tr class='alert alert-info'>
                                <th>COURSE</th>
                                <th>GRADE</th>
                                <th>REMARKS</th>
                                <th>CREDITS</th>
                              </tr>";

                $sql = $this->dbHolder->prepare("SELECT DISTINCT st.studentId, co.courseCode, co.description, sg.fGrade, co.units, co.labUnits
                                          FROM students st, studentgrades sg, config c, courses co
                                            WHERE st.studentId = sg.studentId
                                            AND co.courseCode = sg.courseCode
                                            AND sg.sy = ?
                                            AND sg.sem = ?
                                            AND st.studentId = ?
                                            ORDER BY co.courseCode;");
                $sql->execute(array($sy[0], $sem[0], $studentId));

                while($grades = $sql->fetch()) {
                    $units = $grades[4];
                    if($grades[5] > 0) $units .= "/".$grades[5];
                    $remark = "PASSED";
                    if($grades[3] < 75) $remark = "FAILED";
                    else if($grades[3] == "D") $remark = "DROPPED";

                    $data .= "<tr>
                        <td><label>".$grades[1]."</label><br /><em>".$grades[2]."</em></td>
                        <td>".$grades[3]."</td>
                        <td>".$remark."</td>
                        <td>".$units."</td>
                      </tr>";
                }
            }
        }

        if($data == "") $data = "<h4>Sorry; No records retrieved;</h4>";
        else {
            $data = "
                        ".$data."
                      </table>
                      <p>Note:</p>
                      <p>This document is an original copy and is only valid when it bears the seal of the college / university.<br />
                            Any erasure or alteration made in this copy renders the whole transcript in valid.
                      </p><br />";
            $sql5 = $this->dbHolder->prepare("SELECT * FROM staff WHERE staffId = ?;");
            $sql5->execute(array($_SESSION["staffId"]));
            if($regInfo = $sql5->fetch()) {
                $sql6 = $this->dbHolder->query("SELECT * FROM staff WHERE type='admin';");
                $admin = $sql6->fetch();
                $data .= "<table id='footTbl'>
                            <tr style='padding: 10px;'>
                                <td>Prepared by:</td>
                                <th style='text-decoration: underline;'>".$regInfo[2]." ".$regInfo[3]." ".$regInfo[1]."</th>
                                <td>Date:</td>
                                <td>".$this->getCurrentDate()."</td>
                            </tr>
                            <tr style='padding: 10px;'>
                                <td></td>
                                <td>Branch Registrar</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr style='padding: 10px;'>
                                <td>Checked by:</td>
                                <th style='text-decoration: underline;'>".$admin[2]." ".$admin[3]." ".$admin[1]."</td>
                                <td>Date:</td>
                                <td>".$this->getCurrentDate()."</td>
                            </tr>
                            <tr style='padding: 10px;'>
                                <td></td>
                                <td>School Director</td>
                                <td></td>
                                <td></td>
                            </tr>
                           </table>

                           <style>
                            #footTbl tr td {
                                padding: 10px !important;
                            }
                           </style>";
            }

        }
        $_SESSION["tor"] = "<div style='height: 20000px !important; overflow-y: scroll !important;'>".$data."</div>";
        $this->closeConnection();
    }

    function getCurrentDate() {
        $sql = $this->dbHolder->query("SELECT CURDATE();");
        return $sql->fetch()[0];
    }
} 