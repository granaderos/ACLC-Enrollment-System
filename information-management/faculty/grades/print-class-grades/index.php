<?php
session_start();

if(!isset($_SESSION["type"]))
    header("Location: ../../../login");
else if($_SESSION["type"] != "instructor")
    header("Location: ../../../login");
?>

<html>
    <head>
        <link href="../../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    </head>

    <body class='container-fluid' style="background-image: url(../../files/systemPhotos/aclcBack.png); background-repeat: no-repeat; background-position: center; background-size: 300px">
        <?php if(isset($_SESSION["classGrades"])) echo $_SESSION["classGrades"];
             else echo "<h1>WALA</h1>";
        ?>
    </body>
</html>