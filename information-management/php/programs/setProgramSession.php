<?php
/**
 * Created by PhpStorm.
 * User: Marejean
 * Date: 10/23/16
 * Time: 9:39 PM
 */

session_start();

$_SESSION["progCode"] = $_POST["code"];
$_SESSION["program"] = $_POST["program"];

