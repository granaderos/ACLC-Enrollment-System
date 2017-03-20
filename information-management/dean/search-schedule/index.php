<?php
session_start();

if(!isset($_SESSION["type"]))
    header("Location: ../../login");
else if($_SESSION["type"] != "dean")
    header("Location: ../../login");
?>

<html>
    <head>
        <title>Schedule</title>

        <link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    </head>
    <body style="height: 1000px !important; overflow-y: scroll !important;" class="container-fluid">
        <div class="text-center">
            <h2>ACLC COLLEGE GAPAN CITY</h2>
            <h4>GAPAN CITY</h4>
            <h5>STO. NINO, GAPAN CITY</h5>
            <br />
            <h3>CLASS SCHEDULE</h3>
        </div>
        <div>
            <?php echo $_SESSION["searchSchedule"]; ?>
        </div>
    </body>
</html>