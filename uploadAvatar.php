<?php
// 允许上传的图片后缀
require 'component/user.php';
require 'component/MysqlConnection.php';
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept");

session_start();
//if(!isset($_SESSION['username'])){
//    exit();
//}
echo $_SESSION['username'];
$userID=getID($_SESSION['username']);

$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);

$extension = end($temp);     // 获取文件后缀名
if ((($_FILES["file"]["type"] == "image/gif")
        || ($_FILES["file"]["type"] == "image/jpeg")
        || ($_FILES["file"]["type"] == "image/jpg")
        || ($_FILES["file"]["type"] == "image/png"))
    && ($_FILES["file"]["size"] < 204800)   // 小于 200 kb
    && in_array($extension, $allowedExts)) {
    if ($_FILES["file"]["error"] > 0) {
        statusCode(600,"错误：: " . $_FILES["file"]["error"]);
    } else {
        // 判断当前目录下的 upload 目录是否存在该文件
        // 如果没有 upload 目录，你需要创建它，upload 目录权限为 777
        $_FILES["file"]["name"]=uniqid().'.'.$extension;
        if (file_exists("avatar/" . $_FILES["file"]["name"])) {
            $_FILES["file"]["name"]=uniqid('sbt').'.'.$extension;
        }
        // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
        move_uploaded_file($_FILES["file"]["tmp_name"], "avatar/" . $_FILES["file"]["name"]);

        uploadAvatar($userID,"http//:shaobaitao.cn/forumAPI/avatar/".$_FILES["file"]["name"]);
//        echo "上传文件名: " . $_FILES["file"]["name"] . "<br>";
//        echo "文件类型: " . $_FILES["file"]["type"] . "<br>";
//        echo "文件大小: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
//        echo "文件临时存储的位置: " . $_FILES["file"]["tmp_name"] . "<br>";
    }
}
