<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 3/9/17
 * Time: 12:23 AM
 * To change this template use File | Settings | File Templates.
 */

include_once "../controller/Students.php";

$obj = new Students();
$obj->displaySubmittedReqs();