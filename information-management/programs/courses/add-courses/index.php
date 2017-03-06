<!--
 * Created by PhpStorm.
 * User: Marejean
 * Date: 10/23/16
 * Time: 5:02 PM
 -->
<?php session_start(); ?>
<html>
<head>
    <title>Offered Programs</title>
    <link href="../../../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="../../../css/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
    <link href="../../../css/programs.css" rel="stylesheet" type="text/css"/>

    <script src="../../../js/jquery-1.9.1.min.js" type="text/javascript"></script>
    <script src="../../../js/jquery-ui-1.10.2.min.js" type="text/javascript"></script>
    <script src="../../../js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../../../js/courses.js" type="text/javascript"></script>
</head>
<body>
<link rel="stylesheet" type="text/css" href="../../../css/header.css" />
<div class="header">
    <?php
    if(isset($_SESSION["type"])) {
        echo "<span style='margin-top: 70px; margin-left: 20px; color: #b9def0;' class='pull-left'>
                        <a style='color: #b9def0;' href='../../../registrar'>Home</a> |
                        <a style='color: #b9def0;' href='../../'>Programs</a> |
                        <a style='color: #b9def0;' href='../'>Go Back to the Previous Page</a>
                  </span>";

        echo "<span style='margin-top: 70px; margin-right: 20px; color: #b9def0;' class='pull-right'>Welcome: <label>".$_SESSION["lastname"].", ".$_SESSION["firstname"]." ".$_SESSION["middlename"]."</label>
                  (".strtoupper($_SESSION["type"]).") | &nbsp;
                  <a href='../../../logout.php' style='color: red;'>
                        Log-out
                  </a></span>";
    }
    ?>
</div>
<div id="programMainContainer" class="container-fluid">
    <!-- FOR COURSES -->
    <br />

    <div class="modal fade" id="setDetails">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Set Details for <?php echo $_SESSION["progCode"]; ?> (Adding Courses)</h4>
                </div>
                <div class="modal-body">
                    <div class="panel-body">
                        <h4 class="alert alert-info" id="h4NewPrograms"><?php echo $_SESSION["progDescription"]; ?></h4>
                        <table id="tblProgramSetting" class="form-group">
                            <tr>
                                <td>
                                    <label>Curriculum:</label>
                                </td>
                                <td>
                                    <select class="input form-control" id="selCurYear"></select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Courses Division for: </label>
                                </td>
                                <td>
                                    <select class="input form-control" id="selSOrT">
                                        <option>Semester</option>
                                        <option>Trimester</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>No. of Years:</label>
                                </td>
                                <td>
                                    <select class="input form-control" id="selNoOfYears">
                                        <option>4</option>
                                        <option>3</option>
                                        <option>2</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <button class="btn btn-block btn-lg btn-primary" id="btnSetProgramDetails">Set Details</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="divAddCourses" style="">
        <h4 class="alert alert-info" id="h4NewPrograms">Set-up Courses For <?php echo $_SESSION["progDescription"]; ?></h4>
        <p>
            <em>[Program Details]</em>
            <a data-toggle='modal' href='#setDetails' class='btn btn-xs btn-primary'>
                <span aria-hidden=true class='glyphicon glyphicon-pencil' title='edit'></span> Set Details
            </a>
            <br />
            <table class="table">
                <tr>
                    <td>
                        <label>Curriculum: </label>&nbsp;&nbsp;<span id="progCur" style="text-decoration: underline;">____________________________</span> &nbsp;&nbsp;
                    </td>
                    <td>
                        <label>Semester: </label>&nbsp;&nbsp;<span id="progSem" style="text-decoration: underline;">____________________________</span> &nbsp;&nbsp;
                    </td>
                    <td>
                        <label>No. Of Years: </label>&nbsp;&nbsp;<span id="progYear" style="text-decoration: underline;">____________________________</span>
                    </td>
                </tr>
            </table>
        </p>

        <table style="" class="table table-striped" id="tblAddedCourses"></table>

        <table style="overflow-y: scroll;" id="tblForCourseEncoding" class="table table-responsive">
            <tr>
                <th class="text-center alert alert-danger" colspan="4">
                    Year: [ <span id="currentYear">__</span> ]&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                    <span id="progSem"></span>Semester: [ <span id="currentSem">__</span> ]
                </th>
            </tr>
            <tr id="addingCoursesHeader" class="alert alert-info">
                <th>Course Code</th>
                <th>Description</th>
                <th>Units</th>
                <th>Pre-requisite(s)</th>
            </tr>
            <tbody id="coursesTbody"></tbody>
            <tbody id="addingCoursesTbody" class="form-inline">
                <tr>
                    <td><input id="txtCourseCode" class="form-control input input-sm" placeholder="course Code" required/></td>
                    <td><input id="txtDescription" class="form-control input input-sm" placeholder="Description" required/>
                    </td>
                    <td><input id="txtUnit" class="form-control input input-sm" placeholder="Lecture" required/>
                        <input id="txtUnitLab" class="form-control input input-sm" placeholder="Lab" required/>
                    </td>
                    <td>
                        <span id="pPreReqs"></span>
                        <select id="selPreReq" onchange="selPreReq()" class="form-control input input-sm"></select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><label>Total Units: &nbsp;&nbsp;<span style="text-decoration: underline;" id="totalUnits">0</span></label></td>
                    <td></td>
                </tr>
                <tr>
                    <th class="text-center" colspan="4">
                        <button class="btn btn-primary" id="btnAddCourse" onclick="addTempCourse()">
                            Add Course
                        </button>
                        <button class="btn btn-danger" id="btnSaveCourses" onclick="saveCourses()">
                            Save Courses For <span id="btnProgValue">____</span>
                        </button>
                    </th>
                </tr>
            </tbody>
        </table>
    </div>

</div>
<!-- end of program main container -->
<div id="divDialog"></div>

</body>
</html>

<script type="text/javascript">
    $(document).ready(function () {
        displayAvailableCurriculum();
        $("#setProgDetailsBeforeAddingCourses").toggle();

    });
    function displayAvailableCurriculum() {
        $.ajax({
            type: "POST",
            url: "../../../php/students/displayAvailableCurriculum.php",
            success: function (data) {
                $("#selCurYear").html(data);
            },
            error: function (data) {
                alert("error  " + JSON.stringify(data))
            }
        })
    }
</script>