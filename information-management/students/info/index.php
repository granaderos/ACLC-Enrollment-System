<?php
session_start();

if(!isset($_SESSION["type"]))
    header("Location: ../login");
?>

<html>
    <head>
        <link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../../css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
        <link href="../../css/jquery-ui.min.css" rel="stylesheet" type="text/css" />
        <link href="../../css/registrar.css" rel="stylesheet" type="text/css" />

        <script src="../../js/jquery-1.9.1.min.js" type="text/javascript"></script>
        <script src="../../js/jquery-ui-1.10.2.min.js" type="text/javascript"></script>
        <script src="../../js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../../js/students.js" type="text/javascript"></script>
    </head>
    <body>
    <link rel="stylesheet" type="text/css" href="../../css/header.css" />
    <div class="header">
        <?php
        if(isset($_SESSION["type"])) {
            echo "<span style='margin-top: 70px; margin-left: 20px; color: #b9def0;' class='pull-left'>
                        <a style='color: #b9def0;' href='../../registrar'>Home</a> |
                        <a style='color: #b9def0;' href='../'>Go Back to the Previous Page</a>
                  </span>";

            echo "<span style='margin-top: 70px; margin-right: 20px; color: #b9def0;' class='pull-right'>Welcome: <label>".$_SESSION["lastname"].", ".$_SESSION["firstname"]." ".$_SESSION["middlename"]."</label>
                  (".strtoupper($_SESSION["type"]).") | &nbsp;
                  <a href='../logout.php' style='color: red;'>
                        Log-out
                  </a></span>";
        }
        ?>
    </div>
        <div class="container-fluid">
            <h2>Student Information</h2>

            <label>Student No.: </label> &nbsp;&nbsp;<span id="studStudNo"></span> <br />
            <label>Program: </label> &nbsp;&nbsp;<span id="studProg"></span> <br />
            <label>Curriculum: </label> &nbsp;&nbsp;<span id="studCur"></span> <br />
            <label>Year Level: </label> &nbsp;&nbsp;<span id="studYear"></span> <br />

            <h3 class="alert alert-info">Personal Background</h3>
            <label>Name: </label> &nbsp;&nbsp;<span id="studName"></span> <br />
            <label>Gender: </label> &nbsp;&nbsp;<span id="studGender"></span> <br />
            <label>Address: </label> &nbsp;&nbsp;<span id="studAddress"></span> <br />
            <label>Contact Number: </label> &nbsp;&nbsp;<span id="studContact"></span> <br />
            <label>Birth Date: </label> &nbsp;&nbsp;<span id="studBirthData"></span> <br />
            <label>Age: </label> &nbsp;&nbsp;<span id="studAge"></span> <br />
            <label>Birth Place: </label> &nbsp;&nbsp;<span id="studBirthPlace"></span> <br />
            <label>Nationality: </label> &nbsp;&nbsp;<span id="studNationality"></span> <br />

            <h3 class="alert alert-info">Education Background</h3>
            <label>Secondary School: </label> &nbsp;&nbsp;<span id="studSecSchool"></span> <br />
            <label>Date Graduated: </label> &nbsp;&nbsp;<span id="studSecDate"></span> <br />
            <label>School Last Attended: </label> &nbsp;&nbsp;<span id="studSchoolLastAttended"></span> <br />
            <label>Date Last Attended: </label> &nbsp;&nbsp;<span id="studLastAttendedDate"></span> <br />

            <h3 class="alert alert-info">Guardian's Information</h3>
            <label>Name: </label> &nbsp;&nbsp;<span id="gName"></span> <br />
            <label>Address: </label> &nbsp;&nbsp;<span id="gAddress"></span> <br />
            <label>Contact Number: </label> &nbsp;&nbsp;<span id="gContactNumber"></span> <br />
            <label>Relationship: </label> &nbsp;&nbsp;<span id="gRelationship"></span> <br />

            <table id="tblStudInfo">

            </table>
        </div>
    </body>
</html>

<script type="text/javascript">
    $(document).ready(function() {
        getStudentInfo();
    });
</script>