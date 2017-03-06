<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 2/24/17
 * Time: 5:34 PM
 * To change this template use File | Settings | File Templates.
 */

include_once "../controller/Dean.php";

$obj = new Dean();
$obj->deleteCourseSchedule($_POST["schedId"]);