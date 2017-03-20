<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 3/6/17
 * Time: 3:15 PM
 * To change this template use File | Settings | File Templates.
 */

include_once "../controller/Programs.php";

$obj = new Programs();
$obj->displayPrevAddedCourses($_POST["progCur"], $_POST["progSem"], $_POST["progYear"]);