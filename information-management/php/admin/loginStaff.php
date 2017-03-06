<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 1/23/17
 * Time: 9:58 PM
 * To change this template use File | Settings | File Templates.
 */

include_once "../controller/Admin.php";

$obj = new Admin();
$obj->loginStaff($_POST["username"], $_POST["password"]);

