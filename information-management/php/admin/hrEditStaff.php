<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 2/17/17
 * Time: 11:10 PM
 * To change this template use File | Settings | File Templates.
 */

include_once "../controller/Admin.php";

$obj = new Admin();
$obj->hrEditStaff($_POST["staffId"]);