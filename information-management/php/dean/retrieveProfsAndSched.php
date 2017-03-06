<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 2/22/17
 * Time: 8:38 AM
 * To change this template use File | Settings | File Templates.
 */

include_once "../controller/Dean.php";

$obj = new Dean();
$obj->retrieveProfsAndSched($_POST["sectionId"], $_POST["courseCode"]);


