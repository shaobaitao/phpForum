<?php
session_start();

if(isset($_SESSION['username'])){
    $msg=$_SESSION['username'];
    $is_login=true;
}else{
    $msg='no sessions';
    $is_login=false;
}

$str = array
(
    'msg'=>$msg,
    'is_login'=>$is_login,
//    'last_login'=>$username,
);
echo json_encode($str,256);