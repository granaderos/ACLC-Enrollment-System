<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 2/28/17
 * Time: 11:02 PM
 * To change this template use File | Settings | File Templates.
 */

session_start();

$_SESSION["classSection"] = $_POST["sectionId"];
$_SESSION["classCourse"] = $_POST["courseCode"];