<?php


require 'component/MysqlConnection.php';
header('Content-Type:text/json;charset=utf-8');
$data = file_get_contents("php://input");
$data = json_decode($data, true);

$username = trim($data['name']);
$password = trim($data['pass']);

$sql = "select * from forum.forum_user where username='$username' and password='$password'";

if (isset($conn)) {
    $result = mysqli_query($conn, $sql);
}
$row = mysqli_fetch_assoc($result);
if ($row == null) {
    $code = 401;
    $msg = "登录失败！请检查账号秘密再登录！";
} else {
    session_start();
    $_SESSION['userInfo'] = $username;

    $code = 200;
    $msg = "登录成功！";
}
$str = array
(
    'code'=> $code,
    'msg' => $msg,
);
echo json_encode($str, 256);