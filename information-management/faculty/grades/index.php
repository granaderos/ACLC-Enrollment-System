<?php
session_start();

if(!isset($_SESSION["type"]))
    header("Location: ../../login");
else if($_SESSION["type"] != "instructor")
    header("Location: ../../login");

?>

<html>
    <head>
        <title>Encode Grades | <?php echo $_SESSION["classSection"]."-".$_SESSION["classCourse"] ?></title>

        <script src="../../js/jquery-1.12.4.min.js" type="text/javascript"></script>
        <script src="../../js/jquery-ui-1.12.1.min.js" type="text/javascript"></script>
        <script src="../../js/bootstrap.min.js" type="text/javascript"></script>

        <link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../../css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
        <link href="../../css/jquery-ui.min.css" rel="stylesheet" type="text/css" />
        <link rel="icon" type="image/png" href="../../../aclc.png" />

        <link href="../../faculty.css" type="text/css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="../../css/navs.css" />
        <link rel="stylesheet" type="text/css" href="../../css/header.css" />
        <script src="../../js/grades.js"></script>

    </head>
    <body>
        <div class="header">
            <p class="pull-right" style="margin-top: 70px; margin-right: 20px; color: #b9def0;">
                <?php
                if(isset($_SESSION["type"])) {
                    echo "Welcome: <label>".$_SESSION["lastname"].", ".$_SESSION["firstname"]." ".$_SESSION["middlename"]."</label>
                      (".strtoupper($_SESSION["type"]).") | &nbsp;
                      <a href='../../logout.php' style='color: red;' class='pull-right'>
                            Log-out
                      </a>";
                }
                ?>
            </p>
        </div>
        <div id="navDiv">
            <div id="info">
                <img src="../../files/systemPhotos/aclclogo.png" width="100%" height="100%" style="border-radius: 7px 7px 0 0;" class="image-responsive"><br />
            </div>
            <div id="nav">
                <ul>
                    <a onclick="toggleHome()"><li>Home</li></a>
                    <a onclick="toggleEncodeGrades()"><li>Encode Grades</li></a>
                    <a onclick="toggleClassList()"><li>View Class List</li></a>
                    <a onclick="toggleSchedule()"><li>Schedule</li></a>
                    <a href="../../logout.php"><li>Log Out</li></a>
                </ul>
            </div>
        </div>

        <div class="mainContainer" id="divGradeMainContainer">
        </div>
    </body>
</html>