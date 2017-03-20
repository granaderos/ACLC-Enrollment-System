<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 3/5/17
 * Time: 10:59 AM
 * To change this template use File | Settings | File Templates.
 */

include_once "../controller/Dean.php";

$obj = new Dean();
$obj->showStudentPreregistration($_POST["studentId"]);