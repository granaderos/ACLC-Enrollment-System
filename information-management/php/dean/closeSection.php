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
$obj->closeSection($_POST["sectionCode"], $_POST["sy"], $_POST["sem"]);