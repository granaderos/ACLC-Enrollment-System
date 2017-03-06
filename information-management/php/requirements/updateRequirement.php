<?php
/**
 * Created by PhpStorm.
 * User: Marejean
 * Date: 10/23/16
 * Time: 2:18 PM
 */
include_once "../controller/Requirements.php";
$obj = new Requirements();
$obj->updateRequirement($_POST["id"], $_POST["newReq"]);