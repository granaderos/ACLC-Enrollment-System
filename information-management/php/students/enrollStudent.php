<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 2/8/17
 * Time: 12:26 AM
 * To change this template use File | Settings | File Templates.
 */

include_once "../controller/Students.php";
$obj = new Students();
$obj->enrollStudent($_POST["firstName"], $_POST["middleName"], $_POST["lastName"], $_POST["id"], $_POST["program"],
    $_POST["curriculum"], $_POST["year"], $_POST["type"], $_POST["reqsIds"], $_POST["section"]);