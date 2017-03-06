<?php
/**
 * Created by PhpStorm.
 * User: Marejean
 * Date: 10/23/16
 * Time: 9:39 PM
 */

include_once "../controller/Programs.php";
$obj = new Programs();
$obj->addCourse($_POST["year"], $_POST["sem"], $_POST["curYear"], $_POST["cur"], $_POST["code"], $_POST["description"], $_POST["unit"], $_POST["preReq"]);