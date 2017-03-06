<html>
    <head>
        <title>ACLC | Admin</title>

        <?php include_once "../misc/imports.html" ?>
        <link type="text/css" rel="stylesheet" href="../css/admin.css">
        <script src="../js/admin.js" type="text/javascript"></script>

        <?php include_once "../misc/header.php";?>
        <?php include_once "../navs/adminNav.html";?>
    </head>
    <body>

        <div id="adminMainContainer">
            <center>
                <p id="instruction"><b>Add Employee Information</b></p>
            <form>
                <table >
                   <tr>
                       <td>First Name: </td><td><input type="text" name="firstName" id="firstName" class="field"/></td>
                   </tr>
                    <tr>
                        <td>Middle Name:</td><td><input type="text" name="lastName" id="middleName" class="field" /> </td>
                    </tr>
                    <tr>
                        <td>Last Name:</td><td><input type="text" name="lastName" id="lastName" class="field"/></td>
                    </tr>
                    <tr>
                        <td>Type:</td>
                        <td>
                            <select name="type" id="type" class="field">
                                <option value="HR">HR</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                    <td>Username </td><td><input type="text" name="username" id="username" class="field"/></td>
                    </tr>
                </table>

                <button id="addStaff" class="button">Submit</button>
            </form>
            </center>
        </div>

        <div class="modal fade" id="adminViewStaff">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">ACLC Employees</h4>
                    </div>
                    <div class="modal-body">
                        <div class="panel-body">
                            <table class="table table-responsive table-striped table-hover" id="tblAdminViewStaff"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>