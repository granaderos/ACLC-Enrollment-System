<!--
 * Created by PhpStorm.
 * User: Marejean
 * Date: 10/23/16
 * Time: 5:02 PM
 -->
<?php
session_start();

if(!isset($_SESSION["type"]))
    header("Location: ../../login");
?>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <title>Offered Programs</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="../../css/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
    <link href="../../css/programs.css" rel="stylesheet" type="text/css"/>

    <script src="../../js/jquery-1.12.4.min.js" type="text/javascript"></script>
    <script src="../../js/jquery-ui-1.12.1.min.js" type="text/javascript"></script>
    <script src="../../js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../../js/courses.js" type="text/javascript"></script>
</head>
<body>
<link rel="stylesheet" type="text/css" href="../../css/header.css" />
<div class="header">
        <?php
        if(isset($_SESSION["type"])) {
            echo "<span style='margin-top: 70px; margin-left: 20px; color: #b9def0;' class='pull-left'>
                        <a style='color: #b9def0;' href='../../registrar'>Home</a> |
                        <a style='color: #b9def0;' href='../'>Go Back to Programs</a>
                  </span>";

            echo "<span style='margin-top: 70px; margin-right: 20px; color: #b9def0;' class='pull-right'>Welcome: <label>".$_SESSION["lastname"].", ".$_SESSION["firstname"]." ".$_SESSION["middlename"]."</label>
                  (".strtoupper($_SESSION["type"]).") | &nbsp;
                  <a href='../logout.php' style='color: red;'>
                        Log-out
                  </a></span>";
        }
        ?>
</div>
<div id="programMainContainer" style="margin-top: 100px;" class="container-fluid">
    <div class="form-inline">
        <h2>Courses for <span id="spanProg"><?php if(isset($_SESSION["program"])) echo $_SESSION["program"]; ?></span></h2>
        <label>Curriculum: </label>
        <select class="form-control" onchange="displayProg()" name="selCurriculumToDisplay" id="selCurriculumToDisplay"></select>
        <input class="form-control" style='width: 25px; height: 25px;' type='radio' name='radioProgToDisplay' checked
               onchange='displayCoursesSem()'/> <label>Semestral</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input class="form-control" style='width: 25px; height: 25px;' type='radio' name='radioProgToDisplay'
               onchange='displayCoursesTri()'/><label>Trimestral</label>

        <div class="container-fluid" id="divCoursesMainContainer">

        </div>
    </div>

    <!-- FOR COURSES -->

</div>
<!-- end of program main container -->
<div id="divDialog"></div>

</body>
</html>

<script type="text/javascript">
    $(document).ready(function() {
        displayCurriculumOptions();
    });
</script>