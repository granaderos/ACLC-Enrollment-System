<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 2/21/17
 * Time: 6:04 AM
 * To change this template use File | Settings | File Templates.
 */

include_once "../controller/Dean.php";

$obj = new Dean();
$obj->addSchedule($_POST["sectionId"], $_POST["course"], $_POST["day"], $_POST["startTime"], $_POST["endTime"], $_POST["room"]);