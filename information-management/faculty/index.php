<?php
session_start();

if(!isset($_SESSION["type"]))
    header("Location: ../login");
else if($_SESSION["type"] != "instructor")
    header("Location: ../login");

$inactive = 600;
if(!isset($_SESSION['timeout']) )
    $_SESSION['timeout'] = time() + $inactive;

$sessionLife = time() - $_SESSION['timeout'];

if($sessionLife > $inactive) {
    header("Location: ../logout.php");
}

$_SESSION['timeout']=time();

?>

<html>
    <head>
        <title>ACLC | Faculty</title>

        <?php include_once "../misc/imports.html"; ?>
        <link type="text/css" rel="stylesheet" href="../faculty.css" />
        <script src="../js/faculty.js"></script>

        <?php include_once "../misc/header.php"?>
        <?php include_once "../navs/instructorNav.html"?>

        <script type="text/javascript">
            $(document).ready(function() {
                getSectionToEncode();
                getClassList();
                displayTodaySched();
                displaySchedule();
                toggleHome();
            });
        </script>

    </head>
    <body>
        <div class="mainContainer" id="divProfMainContainer" class="container-fluid"></div>

        <div id="divHomeContainer" class="hidden">
            <h2>Today's Schedule </h2>
            <p><label id="schedDay"></label></p>
            <div id="homeSchedContainer"></div>
        </div>

        <div id="divScheduleContainer" class="hidden">
            <h2>Weekly Schedule</h2>
            <div id="schedScheduleContainer"></div>
        </div>

        <div id="divClassListContainer" class="hidden">
            <h2>Class List</h2>
            <div id="classListContainer"></div>
        </div>

        <div id="divEncodeGradesContainer" class="hidden">
            <h2>Encode Grades</h2>
            <div id="encodeGradeSectionsContainer"></div>
        </div>

    </body>
</html>