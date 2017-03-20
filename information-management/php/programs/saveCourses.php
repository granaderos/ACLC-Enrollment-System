<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 1/24/17
 * Time: 10:17 AM
 * To change this template use File | Settings | File Templates.
 */

include_once "../controller/Programs.php";

$obj = new Programs();
$obj->saveCourses($_POST["progSem"], $_POST["curHTML"]);
//$obj->addTempCourseToProgram($_POST["code"], $_POST["description"], $_POST["unit"], $_POST["progCur"], $_POST["curYear"], $_POST["curSem"]);