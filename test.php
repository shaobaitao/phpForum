<?php
require 'component/MysqlConnection.php';
require 'component/user.php';
header('Content-Type:text/json;charset=utf-8');
$info = getRegisterInfo();
checkRegisterInfo($info);
register($info);
