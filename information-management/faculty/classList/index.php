<?php
session_start();

if(!isset($_SESSION["type"]))
    header("Location: ../../login");
else if($_SESSION["type"] != "instructor")
    header("Location: ../../login");
?>

<html>
    <head>
        <title><?php echo $_SESSION["classSection"]." | ".$_SESSION["classCourse"]; ?></title>

        <link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <?php echo $_SESSION["classList"]; ?>
    </body>
</html>