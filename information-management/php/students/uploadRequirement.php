<?php
/**
 * Created by PhpStorm.
 * User: Marejean
 * Date: 8/19/16
 * Time: 2:11 PM
 */

include_once "../controller/Students.php";

$studentId = $_POST["studentId"];
$reqId = $_POST["reqId"];
$uniquePhotoName = "";

$allowedImageType = array("image/jpeg", "image/jpg", "image/pjpeg", "image/x-png", "image/png");
$allowedExtension = array("jpeg", "jpg", "png");
$name = explode(".", $_FILES["reqImage"]["name"]);
$extension = end($name);
if(in_array($_FILES["reqImage"]["type"], $allowedImageType) || in_array($extension, $allowedExtension)) {
    if($_FILES["reqImage"]["error"] > 0) {
        $obj = new Students();
        $obj->uploadReqImage("none", "", "");
    } else {
        $uniquePhotoName = $studentId.$reqId.$extension;
        move_uploaded_file($_FILES["reqImage"]["tmp_name"], "../../files/requirements/".$uniquePhotoName);
        $obj = new Students();
        $obj->uploadReqImage($studentId, $reqId, $uniquePhotoName);
    }
}