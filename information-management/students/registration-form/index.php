<?php
session_start();

if(!isset($_SESSION["type"]))
    header("Location: ../../login");
else if($_SESSION["type"] != "registrar")
    header("Location: ../../login");
?>

<html>
    <head>
        <link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    </head>

    <body class='container-fluid' style="background-image: url(../../files/systemPhotos/aclcBack.png); background-repeat: no-repeat; background-position: center; background-size: 300px">
<!--        <img src="../../files/systemPhotos/aclclogo.png" class="image-responsive pull-right" />-->
        <?php if(isset($_SESSION["regForm"])) echo $_SESSION["regForm"]; ?>
    </body>
</html>