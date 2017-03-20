<?php
    session_start();

    if(!isset($_SESSION["type"]))
        header("Location: ../login");

?>

<html>
    <head>
        <?php include_once "../misc/imports.html"; ?>
        <link href="../css/requirements.css" rel="stylesheet" type="text/css" />
        <script src="../js/requirements.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include_once "../misc/header.php"; ?>
        <?php include_once "../navs/registrarNav.html"; ?>
        <div id="divRequirementsMainContainer" style="margin-top: 130px;" class="mainContainer">
            <h2>Student Requiremets</h2><br/>
            <div class="container-fluid">
                <div class="">
                    <table class="table table-responsive" id="tblRequirements"></table>
                </div>

                <div id="divAddRequirement">
                    <h4 class="alert alert-info">Add New Requirement</h4>
                    <form class="" onsubmit="addRequirement(); return false;">
                        <label>Requirement:</label><input class="input input-sm form-control" id="txtRequirement" />
                        <label>For:</label>
                        <select id="selFor" class="input input-sm form-control">
                            <option value="newStudent">New Students</option>
                            <option value="transferee">Transferees</option>
                            <option value="students">Students</option>
                        </select><br />
                        <input type="submit" class="btn btn-primary btn-block" value="Add Student Requirement" />
                    </form>
                </div>

            </div>
            <div id="divDialog"></div>
        </div>
    </body>
</html>