<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 1/23/17
 * Time: 8:56 PM
 * To change this template use File | Settings | File Templates.
 */

include_once "../controller/Admin.php";

$obj = new Admin();
$obj->addStuff($_POST["firstName"], $_POST["middleName"], $_POST["lastName"], $_POST["username"], $_POST["type"]);