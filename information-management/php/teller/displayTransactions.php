<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 3/8/17
 * Time: 8:19 PM
 * To change this template use File | Settings | File Templates.
 */

include_once "../controller/Teller.php";

$obj = new Teller();
$obj->displayTransactions($_POST["name"], $_POST["from"], $_POST["to"]);