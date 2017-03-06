<?php
/**
 * Created by PhpStorm.
 * User: Marejean
 * Date: 10/23/16
 * Time: 9:39 PM
 */

include_once "../controller/Programs.php";
$obj = new Programs();
$obj->deleteProgram($_POST["code"]);