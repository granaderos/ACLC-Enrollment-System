<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 3/14/17
 * Time: 11:51 PM
 * To change this template use File | Settings | File Templates.
 */

include_once "../controller/Faculty.php";

$obj = new Faculty();
$obj->getPrintableGrades($_POST["sectionId"], $_POST["courseCode"], $_POST["staffId"]);