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


$info['location']=$row['location']?$row['location']:'未填写';
$info['nickname']=$row['nickname']?$row['nickname']:'未填写';
$info['gender']=$row['gender']?$row['gender']:'未填写';
$info['email']=$row['email']?$row['email']:'未填写';
$info['headPortrait']=$row['headPortrait']?$row['headPortrait']:'未填写';
$info['birthday']=$row['birthday']?$row['birthday']:'未填写';
$info['introduction']=$row['introduction']?$row['introduction']:'未填写';



echo json_encode($info, 256);
