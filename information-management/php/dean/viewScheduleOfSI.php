<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 3/14/17
 * Time: 1:57 AM
 * To change this template use File | Settings | File Templates.
 */

include_once "../controller/Dean.php";

$obj = new Dean();
$obj->viewScheduleOfSI($_POST["type"], $_POST["id"]);