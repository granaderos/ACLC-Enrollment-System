<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 3/3/17
 * Time: 4:44 AM
 * To change this template use File | Settings | File Templates.
 */

include_once "controller/Student.php";

$obj = new Student();
$obj->chooseSchedule($_POST["courseCode"]);