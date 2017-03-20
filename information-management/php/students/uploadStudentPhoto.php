<?php
/**
 * Created by PhpStorm.
 * User: Marejean
 * Date: 8/19/16
 * Time: 2:11 PM
 */

include_once "../controller/Students.php";

$uniquePhotoName = "";

$allowedImageType = array("image/jpeg", "image/jpg", "image/pjpeg", "image/x-png", "image/png");
$allowedExtension = array("jpeg", "jpg", "png");
$name = explode(".", $_FILES["studImage"]["name"]);
$extension = end($name);
if(in_array($_FILES["studImage"]["type"], $allowedImageType) || in_array($extension, $allowedExtension)) {
    if($_FILES["studImage"]["error"] > 0) {
        $obj = new Students();
        $obj->uploadStudImage("none");
    } else {
        $uniquePhotoName = $_SESSION["regStudentId"].".".$extension;
        move_uploaded_file($_FILES["studImage"]["tmp_name"], "../../files/profiles/".$uniquePhotoName);
        $obj = new Students();
        $obj->uploadStudImage($uniquePhotoName);
    }
}