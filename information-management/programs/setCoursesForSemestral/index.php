<html>
    <hea>
        <title>Semestral Courses</title>
        <link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../../css/jquery-ui.min.css" rel="stylesheet" type="text/css" />
        <link href="../../css/programs.css" rel="stylesheet" type="text/css" />

        <script src="../../js/jquery-1.9.1.min.js" type="text/javascript"></script>
        <script src="../../js/jquery-ui-1.10.2.min.js" type="text/javascript"></script>
        <script src="../../js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../../js/programs.js" type="text/javascript"></script>
    </hea>

    <body>
        <div id="coursesForSemestralMainContainer">
            <table>
                <tr>
                    <td>
                        <table id="tblCoursesForSetUp"></table>
                    </td>
                    <td>
                        <label>Curriculum: </label><span id="curriculumOnSetUp"></span> <br/>
                        <label>Program: </label><span id="programOnSetUp"></span> <br/>
                        <label>Year: </label><span id="yearOnSetUp"></span> <br/>
                        <br/>
                        <table class="table" id="setUpCourses">
                            <tr>
                                <th class="text-center alert-info" colspan="4" >Year:
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
                        </table>
                    </td>
                </tr>
            </table>

        </div>
    </body>
</html>