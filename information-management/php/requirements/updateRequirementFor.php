<?php
/**
 * Created by PhpStorm.
 * User: Marejean
 * Date: 10/23/16
 * Time: 2:29 PM
 */
include_once "../controller/Requirements.php";
$obj = new Requirements();
$obj->updateRequirementFor($_POST["id"], $_POST["newReqFor"]);