<?php
session_start();

if(isset($_SESSION["type"]))
    if(strtoupper($_SESSION["type"]) == "ADMIN")
        header("Location: ../admin");
    else if(strtoupper($_SESSION["type"]) == "REGISTRAR")
        header("Location: ../registrar");
    else if(strtoupper($_SESSION["type"]) == "INSTRUCTOR")
        header("Location: ../instructor");
    else if(strtoupper($_SESSION["type"]) == "DEAN")
        header("Location: ../dean");
    else if(strtoupper($_SESSION["type"]) == "CASHIER")
        header("Location: ../cashier");
?>
<html>
<head>
    <title>ACLC IM | Login</title>
    <?php include_once "../misc/imports.html"; ?>
    <link href="../css/login.css" rel="stylesheet" type="text/css"/>
    <script src="../js/login.js" type="text/javascript"></script>

</head>
<body>
<?php include_once "../misc/header.php";?>
<div id="main-container">
    <div id="left-div">
        <img src="../files/systemPhotos/ACLC.jpg">
        <table>
            <tr>
                <p>
                <td><label>Username: </label></td>
                <td><input type="text" class="form-control" placeholder="username" name="userName" id="userName"></td>
                </p>
            </tr>
            <tr>
                <p>
                <td><label>Password: </label></td>
                <td><input type="password" class="form-control" placeholder="password" name="password" id="password"></td>
                </p>
            </tr>
            <tr>
                <p>
                <td colspan="2">
                    <button id="employeeLogin" name="Submit" class="button">Login</button>
                </td>
                </p>
            </tr>
        </table>
    </div>
</div>
</body>
</html>