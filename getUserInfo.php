<?php
require 'component/MysqlConnection.php';
require 'component/user.php';
$data = file_get_contents("php://input");
$data = json_decode($data, true);

$info['userID'] = (int)trim($data['userID']);

//
session_start();
$info['isOwner']=getUsername($info['userID'])===$_SESSION['username'];

$conn = new Connection();



//$sql = "INSERT INTO forum.forum_user VALUES (null, ? , ? , ? ,null, ? ,0,1,0);";
$sql = "select * from forum.forum_userInfo where userID = ? ";
$stmt = $conn->mysqli->prepare($sql);
$stmt->bind_param("i", $info['userID']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$info['location']=$row['location'];
$info['nickname']=$row['nickname'];
$info['gender']=$row['gender'];
$info['email']=$row['email'];
$info['headPortrait']=$row['headPortrait'];
$info['birthday']=$row['birthday'];
$info['introduction']=$row['introduction'];



echo json_encode($info, 256);
