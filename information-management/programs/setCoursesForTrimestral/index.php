<html>
<hea>
    <title>Trimestral Courses</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../../css/jquery-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="../../css/programs.css" rel="stylesheet" type="text/css" />

    <script src="../../js/jquery-1.12.4.min.js" type="text/javascript"></script>
    <script src="../../js/jquery-ui-1.12.1.min.js" type="text/javascript"></script>
    <script src="../../js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../../js/programs.js" type="text/javascript"></script>
</hea>

<body>
<div id="coursesForTrimestralMainContainer">
    <table>
        <tr>
            <td>
                <table id="tblCoursesForSetUp"></table>
            </td>
            <td>
                <label>Curriculum: </label> <span id="curriculumOnSetUp"></span> <br/>
                <label>Program: </label> <span id="programOnSetUp"></span> <br/>
                <label>Year: </label> <span id="yearOnSetUp"></span> <br/>
                <br/>
                <table>
                    <tr>
                        <th class="text-center alert-info" colspan="4" >Year:
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
                </table>
                <button class="btn-block" onclick="saveCourseForNewCourseDivision()">Save Courses for
                    <script>$("#currentYearOnSetUp").html()</script> Year,
                    <script>$("#currentSemOnSetUp").html()</script> Trimester
                </button>
            </td>
        </tr>
    </table>
</div>
</body>
</html>