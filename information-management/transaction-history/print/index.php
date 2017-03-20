<?php
session_start();

if(!isset($_SESSION["type"]))
    header("Location: ../../login");
else if($_SESSION["type"] != "cashier")
    header("Location: ../../login");
?>

<html>
    <head>
        <link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    </head>

    <body class='container-fluid' style="background-image: url(../../files/systemPhotos/aclcBack.png); background-repeat: no-repeat; background-position: center; background-size: 300px">
        <h1 class='text-center'>ACLC College of Gapan</h1>
        <p class='text-center'>
            <?php if(isset($_SESSION["transTitle"])) echo $_SESSION["transTitle"];
                else echo "<h1>Nothing;</h1>";
            ?>
        </p>
        <?php if(isset($_SESSION["transaction"])) echo $_SESSION["transaction"];
             else echo "<h1>Nothing;</h1>";
        ?>
    </body>
</html>