<?php
session_start();

if(isset($_SESSION['userInfo'])){
    $msg=$_SESSION['userInfo'];
    $is_login=true;
}else{
    $msg='论坛';
    $is_login=false;
}

$str = array
(
    'msg'=>$msg,
    'is_login'=>$is_login,
//    'last_login'=>$username,
);
echo json_encode($str,256);