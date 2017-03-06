<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 1/24/17
 * Time: 5:27 AM
 * To change this template use File | Settings | File Templates.
 */

include_once "../controller/Programs.php";

$obj = new Programs();
$obj->retrieveCourses($_POST["curriculum"], $_POST["courseDivision"], $_POST["program"]);