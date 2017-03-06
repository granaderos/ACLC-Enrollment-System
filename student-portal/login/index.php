<html>
<head>

    <title>ACLC SP | Login</title>

    <?php include_once "../misc/imports.html"; ?>
    <link href="../css/login.css" rel="stylesheet" type="text/css" />
    <script src="../js/login.js" type="text/javascript"></script>

</head>
<body>
<?php include_once "../misc/header.php";?>
<div id="main-container">
    <div id="left-div">
        <img src="../files/systemPhotos/ACLC.jpg">
        <form name="loginInfo" id="loginInfo" onsubmit="login(); return false;">
            <table>
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
</body>
</html>