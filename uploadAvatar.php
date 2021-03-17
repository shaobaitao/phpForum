<?php
// 允许上传的图片后缀
require 'component/user.php';
require 'component/MysqlConnection.php';
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept");

session_start();
if(!isset($_SESSION['username'])){
    exit();
}
//echo $_SESSION['username'];
$userID = getID($_SESSION['username']);

$allowedExts = array("gif", "jpeg", "jpg", "png", "webp");
$temp = explode(".", $_FILES["file"]["name"]);

$extension = end($temp);     // 获取文件后缀名
if ((($_FILES["file"]["type"] == "image/gif")
        || ($_FILES["file"]["type"] == "image/jpeg")
        || ($_FILES["file"]["type"] == "image/jpg")
        || ($_FILES["file"]["type"] == "image/png")
        || ($_FILES["file"]["type"] == "image/webp"))
    && ($_FILES["file"]["size"] <= 512000)   // 小于等于 500 kb
    && in_array($extension, $allowedExts)) {
    if ($_FILES["file"]["error"] > 0) {
        statusCode(600, "错误：: " . $_FILES["file"]["error"]);
    } else {
        // 判断当前目录下的 upload 目录是否存在该文件
        // 如果没有 upload 目录，你需要创建它，upload 目录权限为 777
        $_FILES["file"]["name"] = uniqid() . '.' . $extension;
        if (file_exists("avatar/" . $_FILES["file"]["name"])) {
            $_FILES["file"]["name"] = uniqid('sbt') . '.' . $extension;
        }
        move_uploaded_file($_FILES["file"]["tmp_name"], "avatar/" . $_FILES["file"]["name"]);
        uploadAvatar($userID, "http//:shaobaitao.cn/forumAPI/avatar/" . $_FILES["file"]["name"]);

    }
}
