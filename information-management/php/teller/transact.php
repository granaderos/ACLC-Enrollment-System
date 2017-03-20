<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 3/7/17
 * Time: 9:51 PM
 * To change this template use File | Settings | File Templates.
 */

include_once "../controller/Teller.php";

$obj = new Teller();
$obj->transact($_POST["studentId"], $_POST["currentBalance"], $_POST["needToPay"], $_POST["amountRendered"], $_POST["paymentFor"]);