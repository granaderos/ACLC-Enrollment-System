<?php
/**
 * Created by PhpStorm.
 * User: Marejean
 * Date: 10/23/16
 * Time: 10:10 PM
 */

include_once "../controller/Programs.php";
$obj = new Programs();
$obj->addProgram($_POST["code"], $_POST["description"]);