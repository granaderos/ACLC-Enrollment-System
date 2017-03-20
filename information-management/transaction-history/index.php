
<?php
session_start();

if(!isset($_SESSION["type"]) OR $_SESSION["type"] != "cashier")
    header("Location: ../login");
?>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ACLC | Teller</title>
        <?php include_once "../misc/imports.html"; ?>
        <script src="../js/transReports.js" type="text/javascript"></script>

        <script type="text/javascript">
            $(function () {
                $("#displayFrom").datepicker({ dateFormat: 'yy-mm-dd' });
                $("#displayTo").datepicker({ dateFormat: 'yy-mm-dd' });
            })
        </script>
    </head>
    <body>
        <?php include_once "../misc/header.php"; ?>

        <link rel="stylesheet" type="text/css" href="../css/navs.css" />
        <div id="navDiv">
            <div id="info">
                <center>
                    <img src="../files/systemPhotos/aclclogo.png" width="100%" height="100%"  class="image-responsive"><br />
                </center>
            </div>
            <div id="nav">
                <ul>
                    <a href="../teller" ><li class="topmost">Home</li></a>
                    <a href="../transaction-history"><li>Transaction Reports</li></a>
                    <a href="../logout.php"><li>Log Out</li></a>
                </ul>
            </div>
        </div>
        <div id="tellerMainContainerDiv" class="mainContainer">
            <div id="reportsContainerDiv" class="container-fluid">
                <h2>Transaction Reports</h2>
                <label>Display Student's Transaction History:</label>
                <form onsubmit="displayRecentTransaction(); return false;">
                    <div class="input-group">
                        <span class="input-group-addon" id="searchStud">search</span>
                        <input type="text" class="form-control" id="studTransReport" name="studTransReport" aria-describedby="searchStud" />
                    </div>
                </form>
                <label>Display By Date:</label>
                <div class="row">
                    <div class="col-lg-5">
                        <div class="input-group">
                            <span class="input-group-addon" id="from"><label>From:</label></span>
                            <input type="text" class="form-control" name="displayFrom" id="displayFrom" />
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="input-group">
                            <span class="input-group-addon" id="to"><label>To:</label></span>
                            <input type="text" class="form-control" name="displayTo" id="displayTo" />
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <button class="btn btn-block btn-primary" onclick="displayTransByDate()"><span class="glyphicon glyphicon-eye-open"></span>&nbsp;display</button>
                    </div>
                </div>

                <div id="transactionsContainerDiv"></div>

            </div>
        </div>
    </body>
</html>