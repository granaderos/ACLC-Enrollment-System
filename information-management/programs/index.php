<!--
 * Created by PhpStorm.
 * User: Marejean
 * Date: 10/23/16
 * Time: 5:02 PM
 -->

<?php
session_start();

if(!isset($_SESSION["type"]))
    header("Location: ../login");
?>

<html>
    <head>
        <title>Offered Programs</title>
        <?php include_once "../misc/imports.html"; ?>
        <link href="../css/programs.css" rel="stylesheet" type="text/css" />
        <script src="../js/programs.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include_once "../misc/header.php"; ?>
        <?php include_once "../navs/registrarNav.html"; ?>
        <div class="mainContainer" id="programMainContainer">

            <div class="container-fluid" id="divProgramsSubMenu">
<!--                <a onclick="manipulateCourses()" class="pull-right" style="cursor: pointer;">Manipulate Courses in a Program</a>-->
                <a class="pull-right" style="text-decoration: none;">&nbsp;&nbsp;|&nbsp;&nbsp;</a>
                <a onclick="addPrograms()" class="pull-right" style="cursor: pointer;">Add Program</a>
                <a class="pull-right" style="text-decoration: none;">&nbsp;&nbsp;|&nbsp;&nbsp;</a>
                <a onclick="displayPrograms($('#selCurriculumToDisplay').val())" class="pull-right" style="cursor: pointer;">Display Programs Offered</a>
            </div>
            <div class="container-fluid" id="divProgramsMainContainer">

            </div>

            <div class="container-fluid" id="divProgramsSubContainer" style="display: none;">
                <div id="divDisplayProgramsContainer"></div>
            </div>

            <div class="modal fade" id="editProgram">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">You are About to Modify a Program</h4>
                        </div>
                        <div class="modal-body">
                            <div class="panel-body">
                                <form action="" method="POST" role="form" onsubmit="return false;">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="newProgCode" placeholder="New Program Code" required="">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="newProgDescription" placeholder="New Program Description" required="">
                                    </div>
                                    <input type="hidden" id="prevProgCode">
                                    <button type="submit" class=""  id="btnEditProgram">Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="divAddProgramContent" style="display: none !important;">
                <br />
                <h4 class="alert alert-info">Add New Program</h4>
                <div id="divAddProgram">
                    <form id="formAddProgram" onsubmit="addProgram(); return false;">
                        <label>Program Code:</label><input class="input input-sm form-control" id="txtProgramCode" placeholder="e.g. BSCS" />
                        <label>Description:</label><input class="input input-sm form-control" id="txtProgramDescription" placeholder="e.g. Bachelor of Science in Computer Science" />
                        <br />
                        <input type="submit" class="btn btn-primary btn-block" value="Add New Program" />
                    </form>
                </div>
            </div>
        </div> <!-- end of program main container -->
        <div id="divDialog"></div>
        <input type="hidden" id="curProgram" />
        <input type="hidden" id="curCourseDivision" />
        <input type="hidden" id="possiblePrerequisites" />
        <div id="coursesForSemestralMainContainer" style="display: none;">
            <table class="table">
                <tr>
                    <td>
                        <table class="table table-striped" id="tblCoursesForSetUp"></table>
                    </td>
                    <td>
                        <label>Curriculum: </label><span id="curriculumOnSetUp"></span> <br/>
                        <label>Program: </label><span id="programOnSetUp"></span> <br/>
                        <label>Year: </label><span id="yearOnSetUp"></span> <br/>
                        <br/>
                        <table class="table" id="setUpCourses">
                            <tr>
                                <th class="text-center alert-info" colspan="4" style="font-size: 15px;">Year:
                                    <span id="currentYearOnSetUp">1</span> | Semester:
                                    <span id="currentSemOnSetUp">1</span>
                                </th>
                            </tr>
                            <tr class="alert-danger">
                                <th>Course Code</th>
                                <th>Description</th>
                                <th>Units</th>
                                <th>Pre-requisite(s)</th>
                            </tr>
                            <tbody id="tbodyCoursesOnSetUp"></tbody>
                        </table>
                    </td>
                </tr>
            </table>
            <button id="btnSaveCurrentSemestralCourses" onclick="btnSaveCurrentSemestralCourses()">SAVE COURSES</button>
        </div> <!-- End of Semestral main container -->
        <div id="coursesForTrimestralMainContainer" style="display: none;">
            <table class="table table-hover">
                <tr>
                    <td>
                        <table class="table table-striped" id="tblCoursesForSetUp"></table>
                    </td>
                    <td>
                        <label>Curriculum: </label><span id="curriculumOnSetUp"></span> <br/>
                        <label>Program: </label><span id="programOnSetUp"></span> <br/>
                        <label>Year: </label><span id="yearOnSetUp"></span> <br/>
                        <br/>
                        <table>
                            <tr>
                                <th class="text-center alert-info" colspan="4" style="font-size: 15px;">Year:
                                    <span id="currentYearOnSetUp">1</span> | Trimester:
                                    <span id="currentSemOnSetUp">1</span>
                                </th>
                            </tr>
                            <tr class="alert-danger">
                                <th>Course Code</th>
                                <th>Description</th>
                                <th>Units</th>
                                <th>Pre-requisite(s)</th>
                            </tr>
                            <tbody id="tbodyCoursesOnSetUp"></tbody>
                        </table>
                    </td>
                </tr>
            </table>
            <button id="btnSaveCurrentTrimestralCourses" onclick="btnSaveCurrentTrimestralCourses()">SAVE COURSES</button>
        </div> <!-- end of trimestral main container -->
    </body>

    <script type="text/javascript">
        $(document).ready(function() {
            displayPrograms($('#selCurriculumToDisplay').val());
        });
    </script>
</html>