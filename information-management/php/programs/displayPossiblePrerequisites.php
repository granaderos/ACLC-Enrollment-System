<?php
/**
 * Created by PhpStorm.
 * User: Marejean
 * Date: 12/21/16
 * Time: 2:22 AM
 */

include_once "../controller/Programs.php";
$obj = new Programs();

$obj->displayPossiblePrerequisites($_POST["curriculum"], $_POST["semType"], $_POST["year"], $_POST["sem"]);