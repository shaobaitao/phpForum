<?php
header('Content-Type:text/json;charset=utf-8');
$data = file_get_contents("php://input");
$data = json_decode($data, true);

$username = trim($data['name']);
$password = trim($data['pass']);

// 创建连接
$conn = mysqli_connect("101.37.17.251", "forum", "forum", "forum");
if (!$conn) {
    die("连接失败: " . mysqli_connect_error());
}
$sql = "select * from forum.forum_user where username='$username' and password='$password'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if ($row == null) {
    $msg = '没找到';
} else {
    session_start();
    $_SESSION['userInfo'] = $username;
}
$str = array
(
    'msg' => $msg,
//    'last_login'=>$username,
);
echo json_encode($str, 256);