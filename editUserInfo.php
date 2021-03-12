<?php
require 'component/MysqlConnection.php';
require 'component/user.php';


$data = file_get_contents("php://input");
$data = json_decode($data, true);
$data = $data['editInfo'];
session_start();
if (!getUsername($data['userID']) === $_SESSION['username'] || $_SESSION['username'] == null) {
    statusCode(500, '拒绝访问');
    exit();
}


$conn = new Connection();
$sql = "UPDATE forum.forum_userInfo SET location = ? , nickname= ? ,gender = ? , email = ? , headPortrait = ? , birthday = ? , introduction = ? where userID = ? ";
$stmt = $conn->mysqli->prepare($sql);
$stmt->bind_param("sssssssi", $data['location'], $data['nickname'], $data['gender'], $data['email'], $data['headPortrait'], $data['birthday'], $data['introduction'], $data['userID']);
if ($stmt->execute()) {
    statusCode(200, '修改成功');
} else {
    statusCode(444, '修改失败');
}


