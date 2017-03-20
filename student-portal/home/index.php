<?php
    session_start();
    if(!isset($_SESSION["studentId"])) {
        header("Location: ../login");
    }
?>
<html>
    <head>
        <title>ACLC | Student Portal</title>

        <?php include_once "../misc/imports.html"; ?>
        <link href="../css/home.css" rel="stylesheet" type="text/css" />
        <script src="../js/home.js" type="text/javascript"></script>

    </head>

    <body>
        <?php include_once "../misc/header.php"; ?>
        <?php include_once "../misc/studentNav.html"; ?>

        <div id="studMainContainerDiv" class="mainContainer container-fluid"></div>

        <div id="studClassScheduleContainerDiv" class="hidden">
            <h2>Class Schedule</h2>
            <div id="studSlassScheduleData"></div>
        </div>

        <div id="accountBalanceContainerDiv" class="hidden">
            <h2>Account Balance</h2>
            <div id="accountBalanceDataContainer"></div>
        </div>

        <div id="preregistrationContainerDiv" class="hidden container-fluid" style="margin-left: 260px; margin-top: 20px;">
            <h2>Pre-registration of Courses</h2>

            <div id="pregistrationData"></div>
            <div id="preregController">
                <h3>Total Units Pre-registered:
                    <label class="label label-info">
                        <span id="totalUnitsPrereg">0</span>&nbsp;/&nbsp;<span id="maxUnits">24</span>
                    </label>
                </h3>
                <br />
                <button class="btn btn-block btn-lg btn-primary" onclick="savePreregistration()">Submit Pre-registration</button>
            </div>
            <br /><br /><br /><br />
        </div>

        <div class="modal fade" id="assignScheduleDiv">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Choosing Schedule for <span id='courseToAssignSched'></span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="panel-body">
                            <div id="chooScheduleContainer"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="divAccountSettingContainer" class="container-fluid hidden">
            <p style="margin-top: 10px;">
                <h2>Account Settings</h2>
                <a onclick="editInfoAll();"><span class="glyphicon glyphicon-edit"></span> &nbsp;Edit Information</a>
            </p>
            <form class="form-group" onsubmit="saveStudentInfo(); return false;">
                <h4 class="alert alert-info"><a id="studEditPI" class="glyphicon glyphicon-pencil" title="Edit Personal Information"></a> &nbsp;Personal Information</h4>
                <label>First Name:</label>
                <input class="form-control" id="fName" disabled />
                <label>Middle Name:</label>
                <input class="form-control" id="mName" disabled />
                <label>Last Name:</label>
                <input class="form-control" id="lName" disabled />
                <label>Gender:</label>
                <select class="form-control" id="gender" disabled>
                    <option>Female</option>
                    <option>Male</option>
                </select>
                <label>Address:</label>
                <input class="form-control" id="address" disabled />
                <label>Email Address:</label>
                <input class="form-control" id="emailAddress" disabled />
                <label>Contact Number:</label>
                <input class="form-control" id="contactNumber" disabled />
                <label>Birthday: </label>
                <input class="form-control" id="birthday" disabled />
                <label>Place of Birth:</label>
                <input class="form-control" id="birthPlace" disabled />
                <label>Nationality:</label>
                <input class="form-control" id="nationality" disabled />

                <br />
                <h4 class="alert alert-info"><a id="studEditPI" class="glyphicon glyphicon-pencil" title="Edit Guardian's Information"></a> &nbsp;Gurdian's Information</h4>

                <label>Guardian's First Name:</label>
                <input class="form-control" id="gfName" disabled />
                <label>Guardian's Middle Name:</label>
                <input class="form-control" id="gmName" disabled />
                <label>Guardian's Last Name:</label>
                <input class="form-control" id="glName" disabled />
                <label>Relationship</label>
                <input class="form-control" id="gRelationship" disabled />
                <label>Guardian's Address:</label>
                <input class="form-control" id="gAddress" disabled />
                <label>Guardian'sContact Number:</label>
                <input class="form-control" id="gContactNumber" disabled />

                <br />
                <h4 class="alert alert-info"><a id="studEditEB" class="glyphicon glyphicon-pencil" title="Edit Educational Background"></a> &nbsp;Educational Background</h4>

                <label>Secondary School:</label>
                <input class="form-control" id="sSchool" disabled />
                <label>Year Graduated:</label>
                <input class="form-control" id="sYearGrad" disabled />

                <br/><i>If Transferee:</i><br/>
                <label>School Last Attended:</label>
                <input class="form-control" id="tSchoolAttended" disabled />
                <label>Year Last Attended:</label>

                <input class="form-control" id="tYearAttended" disabled />

                <br />
                <h4 class="alert alert-info"><a id="studEditAC" class="glyphicon glyphicon-pencil" title="Edit Account Credentials"></a> &nbsp;Account Credentials</h4>

                <label>Username:</label>
                <input class="form-control" id="username" disabled />
                <label>Current Password:</label>
                <input class="form-control" id="oldPassword" disabled />
                <label>New Password:</label>
                <input class="form-control" id="newPassword" disabled />
                <label>Confirm New Password:</label>
                <input class="form-control" id="confirmPassword" disabled />

                <br/>
                <button class="btn btn-lg btn-block btn-primary" id="btnSaveStudentInfo">Save Information</button>
            </form>
        </div>

        <div id="homeContainerDiv" class="container-fluid hidden">
            <table class="table table-responsive">
                <tr>
                    <td style="">
                        <div div="profileContainer">
                            <span id="studProfilePhoto">
                                <img src="../files/profiles/mj.png" style="width: 180px; height: 200px;" class="image-responsive" />
                            </span>
                            <p>
                                <label>Name: </label> <span id="profName"></span> <br />
                                <label>Program: </label> <span id="profProgram"></span> <br />
                                <label>Year: </label> <span id="profYear"></span> <br />
                                <label>Semester: </label> <span id="profSem"></span> <br />
                                <label>Total Units Enrolled: </label> <span id="profUnits"></span> <br />
                            </p>
                        </div>
                    </td>
                    <td>
                        <div id="studTodaySched" class="alert alert-danger">
                            <h2>My Class Schedule Today</h2>
<!--                            <label class="alert alert-info" id="sctudSchedDay"></label>-->
                            <div id="studTodaySchedData"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <h2>Currently Enrolled Courses</h2>
                        <div id="currentlyEnrolledCoursesDiv"></div>
                    </td>
                </tr>
            </table>
        </div>

        <div id="gradesContainerDiv" class="hidden container-fluid">
            <h2>My Grades</h2>
            <div id="myGradesContainer"></div>
        </div>

    </body>
</html>