<html>
    <head>

<!--        <link href="../css/jquery-ui.min.css" rel="stylesheet" type="text/css" />-->
        <link href="../css/studentInfo.css" rel="stylesheet" type="text/css" />

        <script src="../js/jquery-1.12.4.min.js" type="text/javascript"></script>
        <script src="../js/jquery-ui-1.12.1.min.js" type="text/javascript"></script>
        <script src="../js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../js/students.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include_once "../misc/header.php";?>
        <?php include_once "../navs/studentNav.html";?>

        <div class="container">
            <div class="panel panel-info">

                <div class="panel-body">
                    <center>
                    <form class="form-group-sm">
                        <h4 class="alert alert-info">Student Information</h4>
                        <label>Student No:</label><input readonly class="input" id="txtNewStudentID" value="000000" /><br>
                        <label>First Name:</label><input class="input" /><br>
                        <label>Middle Name:</label><input class="input" /><br>
                        <label>Last Name:</label><input class="input" /><br>
                        <label>Gender:</label><select class="input"><option>Female</option><option>Male</option></select><br>
                        <label>Nationality:</label><input class="input" /><br>
                        <label>Address:</label><input class="input" /><br>
                        <label>Contact Number:</label><input class="input" /><br>
                        <label>Birthday:</label><input class="input" /><br>
                        <label>Place Of Birth:</label><input class="input" />
                        <h4 class="alert alert-info">Educational Background</h4>
                        <label>Secondary School:</label><input class="input" /><br>
                        <label>Date Graduated:</label><input class="input" /><br>
                        <label>School Last Attended:</label><input placeholder="optional" class="input" /><br>

                       <!-- <h4 class="alert alert-info">Enrollment Information</h4>
                        <label>Program To Enroll:</label><select class="input input-sm form-control" id="selProgram"></select>
                        <label>Curriculum:</label><select class="input input-sm form-control" id="selCurriculum"></select>
                        <label>Year:</label>
                        <select class="input input-sm form-control" id="selYear">
                            <option>1st Year</option>
                            <option>2nd Year</option>
                            <option>3rd Year</option>
                            <option>4th Year</option>
                        </select>
                        <h4 class="alert alert-info">Requirements Submitted</h4>
                        <div>
                        <table id="formReqs" class="table"></table>
                         --->
                </div>
                        <div class="text-center container" id="buttonDiv">
                        <input type="submit" id="button"/>
                        </div>
                    </form>
                </center>
                </div>
            </div>
        </div>



    </body>
</html>