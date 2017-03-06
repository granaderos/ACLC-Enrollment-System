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
        <link href="../css/programs.css" rel="stylesheet" type="text/css" />

        <script src="../js/jquery-1.9.1.min.js" type="text/javascript"></script>
        <script src="../js/jquery-ui-1.10.2.min.js" type="text/javascript"></script>
        <script src="../js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../js/curriculum.js" type="text/javascript"></script>
    </head>
    <body>
    <?php include_once "../misc/header.php"; ?>
    <?php include_once "../navs/registrarNav.html"; ?>
        <div class="mainContainer" id="divProgramsMainContainer">
            <div class="container-fluid">
                <h2>Curriculum</h2><br/>
                <div id="divDisplayCurriculum"></div>
                <div id="divAddCurriculum">
                    <h4 class="alert alert-info">Add New Curriculum</h4>
                    <form id="formAddCurriculum" onsubmit="addCurriculum(); return false;">
                        <label>Curriculum Name | Year:</label><input class="input input-sm form-control" id="txtCurriculumName" placeholder="e.g. 2016" />
                        <label>Description:</label><input class="input input-sm form-control" id="txtCurriculumDescription" placeholder="optional" />
                        <br />
                        <input type="submit" id="btnAddCurriculum" class="btn btn-primary btn-block" value="Add New Curriculum" />
                    </form>
                </div>

                <div id="divDialog"></div>
            </div>
        </div>
    </body>
</html>