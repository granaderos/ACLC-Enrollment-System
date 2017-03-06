<?php
    session_start();

    if(!isset($_SESSION["staffId"])) {
        header("Location: ../index.php");
    }
?>

<html>
<head>
    <title>ACLC | HR</title>

    <?php include_once "../misc/imports.html"; ?>
    <script src="../js/admin.js" type="text/javascript"></script>
    <link href="../css/hr.css" type="text/css" rel="stylesheet">

    <?php include_once "../misc/header.php";?>
    <?php include_once "../navs/hrNav.html";?>
</head>
<body>

<div id="hrMainContainer" class="mainContainer">
        <h2>Faculties and Staff</h2>
        <form class="container-fluid">
            <h4 class="alert alert-info">Add Staff</h4>

            <table class="table table-responsive">
                <tr>
                    <td><label>First Name: </label></td><td><input type="text" class="form-control" name="firstName" id="firstName" class="field"/></td>
                </tr>
                <tr>
                    <td><label>Middle Name: </label></td><td><input type="text" class="form-control" name="lastName" id="middleName" class="field" /> </td>
                </tr>
                <tr>
                    <td><label>Last Name: </label></td><td><input type="text" class="form-control" name="lastName" id="lastName" class="field"/></td>
                </tr>
                <tr>
                    <td><label>Type: </label></td><td><select name="type" id="type" class="form-control">
                    <option value="instructor">Instructor</option>
                    <option value="dean">Dean</option>
                    <option value="registrar">Registrar</option>
                    <option value="cashier">Cashier</option>
                </select> </td>
                </tr>
                <tr>
                    <td><label>Username: </label></td><td><input type="text" name="username" id="username" class="form-control"/></td>
                </tr>
            </table>

            <button id="addStaff" class="button">Submit</button>
        </form>
</div>

<div class="modal fade" id="divHrEditStaff">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">You are About to Modify an Employee's Info</h4>
            </div>
            <div class="modal-body">
                <div class="panel-body">
                    <form class="form-group" action="" method="POST" role="form" onsubmit="return false;">

                        <label>Last Name: </label> <input type="text" class="form-control" id="newLname" required>
                        <label>First Name: </label> <input type="text" class="form-control" id="newFname" required>
                        <label>Middle Name: </label> <input type="text" class="form-control" id="newMname" required>
                        <br />
                        <button class="btn btn-lg btn-default" onclick="hrSaveStaff()" id="btnHrSaveStaff">Save Changes</button>
                        <input type="hidden" id="staffId">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="hidden" id="divHrViewStaff">
    <h2>ACLC Faculties and Staff</h2>
    <table class="table table-hover table-responsive table-striped" id="tblHrViewStaff"></table>
</div>
</body>
</html>