<?php
session_start();

if(!isset($_SESSION["type"]))
    header("Location: ../login");
?>

<html>
    <head>
        <title>ACLC | Dean</title>

        <?php include_once "../misc/imports.html"; ?>
        <link type="text/css" rel="stylesheet" href="../sched.css" />
        <script src="../js/dean.js"></script>

        <?php include_once "../misc/header.php"?>
        <?php include_once "../navs/deanNav.html"?>
    </head>
    <body>
        <div class="mainContainer" id="divDeanMainContainer" class="container-fluid">
            <h2>Programs</h2>
            <em>(Click specific program to view its available sections)</em><br />
            <div id="deanProgramContainer"></div>

            <div id="divProgContainer" style="color: #000000"></div>

            <div id="progSectionsDiv">
                <p>
                    <a data-toggle="modal" href="#addSectionDiv" onclick="displaySYs();"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;Add Section</a>
                </p>
                <h2>Sections for <span id="deanProg"></span></h2>

                <div id="displaySectionsDiv"></div>
            </div>
        </div>

        <div id="garadesEncodingSettingsDiv" class="hidden container-fluid">
            <h2>Grades Encoding Setting</h2>
            <div id="gradesEncodingSettingsData"></div>
        </div>

        <div id="searchScheduleDiv" class="hidden container-fluid">
            <h2>Search Faculty or Students' Schedule</h2>
            <select class='form-control' id='schedToSearch' onclick="getNameSched()">
                <option value="student">Student</option>
                <option value="faculty">Faculty</option>
            </select>
            <input type="text" onkeyup="getNameSched()" placeholder="enter name / student number " class="form-control" id="deanToGetSched" aria-describedby="addonSearch" />

            <div id="schedResultNamesContainer"></div>
        </div>

        <div class="modal fade" id="showStudentRegistrationDiv">
            <div class="modal-dialog text-center">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <p>
                            <h2 class="modal-title" id="studentNamePrereg"></h2>
                            <label>PRE-REGISTRATION</label>
                        </p>
                    </div>
                    <div class="modal-body">
                        <div class="panel-body text-left">
                            <div id="studentRegistrationContainer"></div>
                            <button class="btn btn-block btn-primary" onclick="approveStudentPrereg()">Approve Pre-registration</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="approvePreregContainerDiv" class="hidden container-fluid">
            <h2><?php if(isset($_SESSION["progCodeForApproval"])) echo $_SESSION["progCodeForApproval"]; ?>
                Student Pre-registrations</h2>
            <br />
            <div class="row">
                <div class="col-lg-5">
                    <form onsubmit="getFilteredPreregistrations(); return false;">
                        <div class="input-group">
                          <span class="input-group-btn">
                            <button class="btn btn-primary" type="button"><span class="glyphicon glyphicon-search"></span>
                                &nbsp;&nbsp;search</button>
                          </span>
                            <input type="text" class="form-control" placeholder="Student Name" id="studNameSearch">
                        </div>
                    </form>
                </div>

                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon" id="sizing-addon2">Year Level:</span>
                        <select onchange="getFilteredPreregistrations()" class="form-control" id="selYearLevel" aria-describedby="sizing-addon2">
                            <option value=""></option>
                            <option value=1>1st Year</option>
                            <option value=2>2nd Year</option>
                            <option value=3>3rd Year</option>
                            <option value=4>4th Year</option>
                        </select>
                    </div>
                </div>
            </div>
            <br />
            <div id="approveDeafultDataContainer"></div>
        </div>

        <div class="modal fade" id="approvePreregProgramContainerDiv">
            <div class="modal-dialog text-center">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h2 class="modal-title">Approve Pre-registrations</h2>
                    </div>
                    <div class="modal-body">
                        <div class="panel-body text-left">
                            <h4>Please Choose Program:</h4>
                            <div id="approvePreregProgramContainer"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       <div id="curriculumSettingsContainerDiv" class="container-fluid hidden">
           <h2>Curriculum Settings</h2>
           <div id="curriculumSettingsData" class="container-fluid"></div>
       </div>

        <div id="preregContainerDiv" class='hidden'>
            <h2>Pre-registration Status</h2>
            <table class='table'>
                <tr class='alert alert-info'>
                    <th>Current Status</th>
                    <th>Action</th>
                </tr>
                <tr>
                    <th id="preregStatus"></th>
                    <th id="statusOption"></th>
                </tr>
            </table>
        </div>
        <div id="divViewScheduleContainer" class="hidden">
            <h2><span id="viewScheduleSection"></span> Class Schedule</h2>
            <!--<p>
                <a onclick="" data-toggle="modal" href="#setScheduleDiv"><span class="glyphicon glyphicon-edit"></span>&nbsp;Set Schedule</a>
            </p>-->
            <div id="divCoursesToSetUpSched"></div>

        <div id="scheduleContainer"></div>
        </div>

        <div class="modal fade" id="setScheduleDiv">
            <div class="modal-dialog text-center" style="width: 750px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Setting-up Schedule for Course <label id="setScheduleSection"></label></h4>
                    </div>
                    <div class="modal-body">
                        <div class="panel-body">
                            <div class="form-inline">
                                <label>Day: </label>
                                <!--<select id="schedDay">
                                    <option value=1>Monday</option>
                                    <option value=2>Tuesday</option>
                                    <option value=3>Wednesday</option>
                                    <option value=4>Thursday</option>
                                    <option value=5>Friday</option>
                                    <option value=6>Saturday</option>
                                    <option value=7>Sunday</option>
                                </select>-->
                                <select id="schedDay" class="form-control">
                                    <option>Monday</option>
                                    <option>Tuesday</option>
                                    <option>Wednesday</option>
                                    <option>Thursday</option>
                                    <option>Friday</option>
                                    <option>Saturday</option>
                                    <option>Sunday</option>
                                </select>
                                <label>Start Time: </label>
                                <select id="schedStartTime" onclick="displayEndTime()" class="form-control">
                                    <option value=1>07:00 AM</option>
                                    <option value=2>07:30 AM</option>
                                    <option value=4>08:00 AM</option>
                                    <option value=5>08:30 AM</option>
                                    <option value=6>09:00 AM</option>
                                    <option value=7>09:30 AM</option>
                                    <option value=8>10:00 AM</option>
                                    <option value=9>10:30 AM</option>
                                    <option value=10>11:00 AM</option>
                                    <option value=11>11:30 AM</option>
                                    <option value=12>12:00 PM</option>
                                    <option value=13>12:30 PM</option>
                                    <option value=14>01:00 PM</option>
                                    <option value=15>01:30 PM</option>
                                    <option value=16>02:00 PM</option>
                                    <option value=17>02:30 PM</option>
                                    <option value=18>03:00 PM</option>
                                    <option value=19>03:30 PM</option>
                                    <option value=20>04:00 PM</option>
                                    <option value=21>04:30 PM</option>
                                    <option value=22>05:00 PM</option>
                                    <option value=23>05:30 PM</option>
                                    <option value=24>06:00 PM</option>
                                    <option value=25>06:30 PM</option>
                                    <option value=26>07:00 PM</option>
                                    <option value=27>07:30 PM</option>
                                    <option value=28>08:00 PM</option>
                                    <option value=29>08:30 PM</option>
                                    <option value=30>09:00 PM</option>
                                    <option value=31>09:30 PM</option>
                                </select>
                                <label>End Time: </label>
                                <select id="schedEndTime" class="form-control">
                                    <option value=2>07:30 AM</option>
                                    <option value=3>08:00 AM</option>
                                    <option value=5>08:30 AM</option>
                                    <option value=6>09:00 AM</option>
                                    <option value=7>09:30 AM</option>
                                    <option value=8>10:00 AM</option>
                                    <option value=9>10:30 AM</option>
                                    <option value=10>11:00 AM</option>
                                    <option value=11>11:30 AM</option>
                                    <option value=12>12:00 PM</option>
                                    <option value=13>12:30 PM</option>
                                    <option value=14>01:00 PM</option>
                                    <option value=15>01:30 PM</option>
                                    <option value=16>02:00 PM</option>
                                    <option value=17>02:30 PM</option>
                                    <option value=18>03:00 PM</option>
                                    <option value=19>03:30 PM</option>
                                    <option value=20>04:00 PM</option>
                                    <option value=21>04:30 PM</option>
                                    <option value=22>05:00 PM</option>
                                    <option value=23>05:30 PM</option>
                                    <option value=24>06:00 PM</option>
                                    <option value=25>06:30 PM</option>
                                    <option value=26>07:00 PM</option>
                                    <option value=27>07:30 PM</option>
                                    <option value=28>08:00 PM</option>
                                    <option value=29>08:30 PM</option>
                                    <option value=30>09:00 PM</option>
                                    <option value=31>09:30 PM</option>
                                </select>
                                <label>Room: </label>
                                <select id="schedRoom" class="form-control">
                                    <option>RM1001</option>
                                    <option>RM1002</option>
                                    <option>RM1003</option>
                                    <option>RM1004</option>
                                    <option>RM1005</option>
                                    <option>RM2001</option>
                                    <option>RM2002</option>
                                    <option>RM2003</option>
                                    <option>RM2004</option>
                                    <option>RM2005</option>
                                </select>

                                <br />
                                <br />
                                <button class="btn btn-primary btn-block" onclick="addTempSched()">Add Schedule</button>

                            </div>
                            <br /><br />
                            <table class="table table-striped table-hover">
                                <tr>
                                    <th>Day</th>
                                    <th>Room #</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th></th>
                                </tr>
                                <tbody id="tableTempSched"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="assignProfessorDiv">
            <div class="modal-dialog text-center" style="width: 750px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Assigning Instructor for <label id="assignProfessorCourse"></label></h4>
                    </div>
                    <div class="modal-body">
                        <div class="panel-body text-left">
                            <label>Schedule:</label>
                            <div id="divAssignProfSched"></div>
                            <label>Select Instructor: </label><br/>
                            <select id="selAssignProf" class="form-control">None;</select>
                            <br />
                            <button class="btn btn-primary btn-lg btn-block" onclick="assignProf()">Assign Professor</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addSectionDiv">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Add New Section</h4>
                    </div>
                    <div class="modal-body">
                        <div class="panel-body">
                            <form class="form-group" action="" method="POST" role="form" onsubmit="return false;">
                                <label>Section Code: </label>&nbsp;
                                <input class="form-control" type="text" id="sectionCode" placeholder="Enter Section Name" />
                                <label>Type: </label>&nbsp;
                                <select class="form-control" id="sectionType">
                                    <option value="open">Free Section</option>
                                    <option value="block">Block Section</option>
                                </select>
                                <label>For School Year: </label>
                                <select class="form-control" id="sectionSY"></select>
                                <label>Year Level: </label>
                                <select class="form-control" id="sectionYear">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                                <label>Semester: </label>
                                <select class="form-control" id="sectionSem">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                                <br />
                                <button id="btnAddSection" onclick="addSection()" class="btn btn-primary" >Add Section</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>