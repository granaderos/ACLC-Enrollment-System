<?php
/**
 * Created by PhpStorm.
 * User: Marejean
 * Date: 11/16/16
 * Time: 7:05 PM
 */
include_once "../controller/Programs.php";

$obj = new Programs();
$obj->displayCourses($_POST["curriculum"], $_POST["sem"]);