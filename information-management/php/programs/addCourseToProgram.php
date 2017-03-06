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
$obj->addCourseToProgram($_POST["courseCode"], $_POST["program"], $_POST["curriculum"], $_POST["year"], $_POST["sem"], $_POST["courseDivision"]);