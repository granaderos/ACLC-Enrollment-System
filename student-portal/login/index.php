<html>
<head>

    <title>ACLC SP | Login</title>

    <?php include_once "../misc/imports.html"; ?>
    <link href="../css/login.css" rel="stylesheet" type="text/css" />
    <script src="../js/login.js" type="text/javascript"></script>

</head>
<body>
<?php include_once "../misc/header.php";?>
<div id="main-container" class="mainContainer">
    <div class="row">
        <div class="col-lg-5">
            <div style="" id="left-div">
                <img src="../files/systemPhotos/ACLC.jpg">
            </div>
        </div>

        <div style=" margin-top:  50px;" class="col-lg-5">
                <form name="loginInfo" id="loginForm" onsubmit="login(); return false;">
                    <h2 class='alert alert-info text-center' style="font-size: 30px; font-weight:  bolder; font-family: arial">
                        <span style="color: #2E3191">STUDENT PORTAL</span>
                    </h2>
                    <table>
                        <tr class="alert alert-danger">
                            <th colspan="2">
                                LOGIN
                            </th>
                        </tr>
                        <tr>
                            <td><p id="topmost"><label>Student ID:</label> </td><td><input type="text" placeholder="Student ID" class="form-control" name="studentNum" id="studentNum"></p></td>
                        </tr>
                        <tr>
                            <td><p><label>Username: </label></td><td><input type="text" placeholder="Username" class="form-control" name="userName" id="userName"></p></td>
                        </tr>
                        <tr>
                            <td><p><label>Password: </label></td><td><input type="password" placeholder="Password" class="form-control" name="password" id="password"></p></td>
                        </tr>
                        <tr>
                            <td colspan="2"><input type="submit" id="btnLoginStudent" name="Submit" value="Login" class="button" /></td>
                        </tr>
                    </table>
                </form>

        </div>
    </div>

</div>
</body>
</html>
