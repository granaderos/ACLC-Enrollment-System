<?php
session_start();

if(!isset($_SESSION["type"]))
    header("Location: ../../login");
else if($_SESSION["type"] != "registrar")
    header("Location: ../../login");
?>

<html>
    <head>
        <title>Student's TOR</title>

        <link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    </head>
    <body style='height: 1000px !important; overflow-y: scroll !important;'>
        <?php if(isset($_SESSION["tor"]))echo $_SESSION["tor"];
            else echo "You are not supposed to be here yet;";
        ?>
    </body>
</html>
