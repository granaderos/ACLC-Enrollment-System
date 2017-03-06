<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 2/24/17
 * Time: 7:09 PM
 * To change this template use File | Settings | File Templates.
 */

include_once "controller/Student.php";

$obj = new Student();
$obj->saveStudentInfo($_POST["address"], $_POST["emailAddress"], $_POST["contactNumber"], $_POST["birthday"],
                        $_POST["gender"], $_POST["birthPlace"], $_POST["nationality"], $_POST["gfName"],
                        $_POST["gmName"], $_POST["glName"], $_POST["gRelationship"], $_POST["gAddress"],
                        $_POST["gContactNumber"], $_POST["sSchool"], $_POST["sYearGrad"], $_POST["tSchoolAttended"],
                        $_POST["tYearAttended"], $_POST["username"], $_POST["oldPassword"], $_POST["newPassword"]);