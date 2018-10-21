<?php
/**
 * Created by PhpStorm.
 * User: Marejean
 * Date: 3/21/17
 * Time: 1:25 PM
 */

include_once "../controller/Teller.php";

$obj = new Teller();
$obj->editFdownpayment($_POST["dp"]);