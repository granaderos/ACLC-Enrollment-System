


<link rel="stylesheet" type="text/css" href="../css/header.css" />
<div class="header">
    <p class="pull-right" style="margin-top: 70px; margin-right: 20px; color: #b9def0;">
    <?php
        if(isset($_SESSION["studentId"])) {
            echo "Welcome: <label>".$_SESSION["lastname"].", ".$_SESSION["firstname"]." ".$_SESSION["middlename"]." (".$_SESSION["username"].")</label>
                   | &nbsp;
                  <a href='../logout.php' style='color: red;' class='pull-right'>
                        Log-out
                  </a>";
        }
    ?>
    </p>
</div>