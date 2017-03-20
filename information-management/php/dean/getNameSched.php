<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 3/13/17
 * Time: 6:06 AM
 * To change this template use File | Settings | File Templates.
 */

include_once "../controller/Dean.php";
$obj = new Dean();
$obj->getNameSched($_POST["keyWord"], $_POST["toSearch"]);