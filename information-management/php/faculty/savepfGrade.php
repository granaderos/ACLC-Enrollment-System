<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 3/2/17
 * Time: 1:34 AM
 * To change this template use File | Settings | File Templates.
 */

include_once "../controller/Faculty.php";
$obj = new Faculty();
$obj->savepfGrade($_POST["studentId"], $_POST["courseCode"], $_POST["grade"]);