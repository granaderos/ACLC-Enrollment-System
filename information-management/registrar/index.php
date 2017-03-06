<?php
    session_start();

    if(!isset($_SESSION["type"]))
        header("Location: ../login");

?>

<html>
    <head>
        <?php include_once "../misc/imports.html"; ?>
        <link href="../css/registrar.css" rel="stylesheet" type="text/css" />
        <script src="../js/students.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include_once "../misc/header.php"; ?>
        <?php include_once "../navs/registrarNav.html"; ?>
        <div id="divRegistrarMainContainer" class="mainContainer">
            <h2>Enroll Student</h2><br/>
            <div class="container-fluid">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center">Student Encoding</h3>
                    </div>
                    <div class="panel-body">
                        <form class="form-group-sm" onsubmit="return false;">
                            <h4 class="alert alert-info">Student Information</h4>
                            <label>Student No:</label><input readonly class="input input-sm form-control" id="txtNewStudentID" value="000000" />
                            <label>First Name:</label><input id="firstName" class="input input-sm form-control" />
                            <label>Middle Name:</label><input id="middleName" class="input input-sm form-control" />
                            <label>Last Name:</label><input id="lastName" class="input input-sm form-control" />

                            <!--<label>Gender:</label><select class="input input-sm form-control"><option>Female</option><option>Male</option></select>
                            <label>Nationality:</label><input class="input input-sm form-control" />
                            <label>Address:</label><input class="input input-sm form-control" />
                            <label>Contact Number:</label><input class="input input-sm form-control" />
                            <label>Birthday:</label><input class="input input-sm form-control" />
                            <label>Place Of Birth:</label><input class="input input-sm form-control" />
                            <h4 class="alert alert-info">Educational Background</h4>
                            <label>Secondary School:</label><input class="input input-sm form-control" />
                            <label>Date Graduated:</label><input class="input input-sm form-control" />
                            <label>School Last Attended:</label><input placeholder="optional" class="input input-sm form-control" />-->

                            <h4 class="alert alert-info">Enrollment Information</h4>
                            <label>Type:</label>
                            <select class="input input-sm form-control" id="selType">
                                <option value="newStudent">New Student</option>
                                <option value="transferee">Transferee</option>
                            </select>
                            <label>Program To Enroll:</label><select onchange="displayAvailableSections()" class="input input-sm form-control" id="selProgram"></select>
                            <label>Curriculum:</label><select class="input input-sm form-control" id="selCurriculum"></select>
                            <label>Year:</label>
                            <select class="input input-sm form-control" id="selYear">
                                <option>1st Year</option>
                                <option>2nd Year</option>
                                <option>3rd Year</option>
                                <option>4th Year</option>
                            </select>
                            <label>Assign Section:</label>
                            <select class="input input-sm form-control" id="selSection"></select>
                            <h4 class="alert alert-info">Requirements Submitted</h4>
                            <div>
                                <table id="tblReqs" class="table"></table>
                            </div>
                            <input onclick="enrollStudent()" value="Enroll Student" type="submit"  class='btn-block text-center btn btn-primary'/>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="divRecentEnrollee" class="hidden">
            <h3>Recently Enrolled Students:</h3>
            <div id="divRecentlyEnrolledStudents"></div>
        </div> <!-- divRecentEnrollee ends -->

        <div id="divStudInfoContainer" class="hidden">
            <h2>Student Information</h2>

            <label>Student No.: </label> &nbsp;&nbsp;<span id="studStudNo"></span> <br />
            <label>Program: </label> &nbsp;&nbsp;<span id="studProg"></span> <br />
            <label>Curriculum: </label> &nbsp;&nbsp;<span id="studCur"></span> <br />
            <label>Year Level: </label> &nbsp;&nbsp;<span id="studYear"></span> <br />

            <h3 class="alert alert-info">Personal Background</h3>
            <label>Name: </label> &nbsp;&nbsp;<span id="studName"></span> <br />
            <label>Gender: </label> &nbsp;&nbsp;<span id="studGender"></span> <br />
            <label>Address: </label> &nbsp;&nbsp;<span id="studAddress"></span> <br />
            <label>Contact Number: </label> &nbsp;&nbsp;<span id="studContact"></span> <br />
            <label>Birth Date: </label> &nbsp;&nbsp;<span id="studBirthData"></span> <br />
            <label>Age: </label> &nbsp;&nbsp;<span id="studAge"></span> <br />
            <label>Birth Place: </label> &nbsp;&nbsp;<span id="studBirthPlace"></span> <br />
            <label>Nationality: </label> &nbsp;&nbsp;<span id="studNationality"></span> <br />

            <h3 class="alert alert-info">Educational Background</h3>
            <label>Secondary School: </label> &nbsp;&nbsp;<span id="studSecSchool"></span> <br />
            <label>Date Graduated: </label> &nbsp;&nbsp;<span id="studSecDate"></span> <br />
            <label>School Last Attended: </label> &nbsp;&nbsp;<span id="studSchoolLastAttended"></span> <br />
            <label>Date Last Attended: </label> &nbsp;&nbsp;<span id="studLastAttendedDate"></span> <br />

            <h3 class="alert alert-info">Guirdian's Information</h3>
            <label>Name: </label> &nbsp;&nbsp;<span id="gName"></span> <br />
            <label>Address: </label> &nbsp;&nbsp;<span id="gAddress"></span> <br />
            <label>Contact Number: </label> &nbsp;&nbsp;<span id="gContactNumber"></span> <br />
            <label>Relationship: </label> &nbsp;&nbsp;<span id="gRelationship"></span> <br />

            <table id="tblStudInfo">

            </table>
        </div> <!-- divStudInfoContainer ends -->

    </body>
</html>

<script type="text/javascript">
    $(document).ready(function() {
        displayNewStudentID();
        displayStudentRequirements();
        displayAvailablePrograms();
        displayAvailableCurriculum();
    });
</script>