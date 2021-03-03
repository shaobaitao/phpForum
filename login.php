<?php
require 'component/MysqlConnection.php';
require 'component/user.php';
header('Content-Type:text/json;charset=utf-8');
$info = getUserInfo();
checkLoginInfo($info);
checkLogin($info);
setCookies($info);
setSessions($info);
updateLogin($info);




