<?php
session_start();

if(!isset($_SESSION["type"]))
    header("Location: ../login");
?>

<html>
    <head>
        <?php include_once "../misc/imports.html"; ?>
        <link href="../css/registrar.css" rel="stylesheet" type="text/css" />
        <script src="../js/students.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include_once "../misc/header.php"; ?>
        <?php include_once "../navs/registrarNav.html"; ?>
        <div class="mainContainer">
            <div class="container-fluid">

                <div id="divRecentEnrollee">
                    <h3>Recently Enrolled Students:</h3>
                    <div id="divRecentlyEnrolledStudents"></div>
                </div>
            </div>
        </div>
    </body>
</html>

<script type="text/javascript">
    $(document).ready(function() {
        getRecentlyEnrolledStudents();
    });
</script>