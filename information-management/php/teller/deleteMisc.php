<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 3/7/17
 * Time: 8:24 PM
 * To change this template use File | Settings | File Templates.
 */

include_once "../controller/Teller.php";

$obj = new Teller();
$obj->deleteMisc($_POST["miscId"]);