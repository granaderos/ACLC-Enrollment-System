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
            <h2>Student Management</h2>
            <br />
            <div class="container-fluid">
                <form onsubmit="getRecentlyEnrolledStudents(); return false;">
                    <label>Student Name:</label>
                    <div class="input-group">
                        <span class="input-group-addon" id="addonStud">search</span>
                        <input placeholder="filter display by student's name" type="text" style="" class="form-control" id="regStudSearch" aria-describedby="addonStud" />
                    </div>
                </form>
                <p>
                    <label>Display Program: </label>
                    <select id="regDisplayProgram" class="form-control" onchange="getRecentlyEnrolledStudents()">
                        <option></option>
                    </select>
                    <label>Year Level: </label>
                    <select id="regDisplayYear" class="form-control" onchange="getRecentlyEnrolledStudents()">
                        <option></option>
                        <option value=1>1st Year</option>
                        <option value=2>2nd Year</option>
                        <option value=3>3rd Year</option>
                        <option value=4>4th Year</option>
                    </select>
                </p>
                <div id="divRecentEnrollee">
                    <div id="divRecentlyEnrolledStudents"></div>
                </div>
            </div>
        </div>
    </body>
</html>

<script type="text/javascript">
    $(document).ready(function() {
        getRecentlyEnrolledStudents();
        $(document).ready(function() {
            $.ajax({
                type: "POST",
                url: "../php/teller/displayAllProgramsAsOptions.php",
                success: function(data) {
                    if(data != "none")
                        $("#regDisplayProgram").append(data);
                },
                error: function(data) {
                    alert("error in displayAllProgramsAsOptions Mj! :( " + JSON.stringify(data));
                }
            });
        });
    });
</script>