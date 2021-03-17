<?php
require 'component/MysqlConnection.php';
session_start();

if (isset($_SESSION['username'])) {
    $msg = $_SESSION['username'];
    $is_login = true;

    $conn = new Connection();

//    $sql = "update forum.forum_user set last_login= '$now' where username= ? ";
    $sql = "select id from forum.forum_user where username= ? ";
    $stmt = $conn->mysqli->prepare($sql);
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $id = $row['id'];

    $sql = "select headPortrait from forum.forum_userInfo where userID= ? ";
    $stmt = $conn->mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $avatar = $row['headPortrait'];
} else {
    $msg = 'no sessions';
    $is_login = false;
}

$str = array
(
    'id' => $id,
    'msg' => $msg,
    'is_login' => $is_login,
    'avatar' => $avatar,
//    'last_login'=>$username,
);
echo json_encode($str, 256);