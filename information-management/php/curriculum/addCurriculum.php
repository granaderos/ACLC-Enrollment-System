<?php
/**
 * Created by PhpStorm.
 * User: Marejean
 * Date: 10/23/16
 * Time: 10:10 PM
 */

include_once "../controller/Curriculum.php";
$obj = new Curriculum();
$obj->addCurriculum($_POST["name"], $_POST["desc"]);