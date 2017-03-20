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
        <title>Curriculum</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
        <link href="../css/jquery-ui.min.css" rel="stylesheet" type="text/css" />

        <script src="../js/jquery-1.12.4.min.js" type="text/javascript"></script>
        <script src="../js/jquery-ui-1.12.1.min.js" type="text/javascript"></script>
        <script src="../js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../js/tor.js" type="text/javascript"></script>
    </head>
    <body>
    <?php include_once "../misc/header.php"; ?>
    <?php include_once "../navs/registrarNav.html"; ?>
        <div class="mainContainer container-fluid" id="divTorMainContainer">
            <div id="torDiv" class="container-fluid">
                <h2>Transcript of Records</h2>
                <label>Enter Student ID:</label>
                <input type="text" placeholder="Student ID" id="torStudentId" class="form-control" />
                <br />
                <button class="btn btn-primary btn-block" onclick="generateTOR()">Generate T.O.R.</button>
            </div>
            <div id="divDialog"></div>
        </div>
    </body>
</html>