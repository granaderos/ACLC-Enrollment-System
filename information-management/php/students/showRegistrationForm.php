<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 3/7/17
 * Time: 12:05 PM
 * To change this template use File | Settings | File Templates.
 */

include_once "../controller/Students.php";

$obj = new Students();
$obj->showRegistrationForm($_POST["studentId"]);