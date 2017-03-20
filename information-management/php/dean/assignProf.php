<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 2/28/17
 * Time: 1:17 AM
 * To change this template use File | Settings | File Templates.
 */

include_once "../controller/Dean.php";

$obj = new Dean();
$obj->assignProf($_POST["prof"], $_POST["sectionId"], $_POST["courseCode"]);
