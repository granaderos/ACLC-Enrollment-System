<?php
/**
 * Created by PhpStorm.
 * User: Marejean
 * Date: 10/23/16
 * Time: 1:36 PM
 */
include_once "../controller/Requirements.php";
$obj = new Requirements();
$obj->deleteRequirement($_POST["id"]);