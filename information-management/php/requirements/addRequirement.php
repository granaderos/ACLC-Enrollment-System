<?php
/**
 * Created by PhpStorm.
 * User: Marejean
 * Date: 10/23/16
 * Time: 2:25 AM
 */

include_once "../controller/Requirements.php";
$obj = new Requirements();
$obj->addRequirement($_POST["req"], $_POST["forWhom"]);