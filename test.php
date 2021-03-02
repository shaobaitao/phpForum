<?php
require 'component/MysqlConnection.php';
$info = getUserInfo();
checkInfo($info);
if (checkLogin($info)) {
    setCookies($info);
    setSessions($info);
    updateLogin($info);
    echoSuccess();
} else {
    exit();
}

function echoSuccess(){
    $str = array
    (
        'code' => 200,
        'msg' => '登陆成功',
    );
    echo json_encode($str, 256);
}

function updateLogin($info){

    $conn = new Connection();
    $now = date('Y-m-d H:i:s', time());
    $sql = "update forum.forum_user set last_login= '$now' where username= ? ";
    $stmt = $conn->mysqli->prepare($sql);
    $stmt->bind_param("s", $info['username']);
    $stmt->execute();
}

function setSessions($info)
{
    session_start();
    $_SESSION['username'] = $info['username'];
}

function setCookies($info)
{
    if ($info['isSetCookies']) {
        setcookie("userInfoName", $info['username'], time() + 60 * 60 * 24 * 30);
        setcookie("userInfoPass", $info['password'], time() + 60 * 60 * 24 * 30);
    }
}

function getUserInfo()
{
    $info = array(
        'username' => '',
        'password' => '',
        'isSetCookies' => false
    );
    if (isset($_COOKIE['userInfoName'])) {
        $info['username'] = $_COOKIE["userInfoName"];
        $info['password'] = $_COOKIE["userInfoPass"];
    } else {
        $data = file_get_contents("php://input");
        $data = json_decode($data, true);

        $info['username'] = trim($data['name']);
        $info['password'] = trim($data['pass']);
        $info['isSetCookies'] = $data['isSetCookies'];
    }
    return $info;
}

function checkInfo($info)
{
    $str = array
    (
        'code' => '',
        'msg' => '',
    );
    if ($info['username'] == null) {
        $str['code'] = 400;
        $str['msg'] = "账号为空";
    } elseif (!preg_match('/^\w+$/', $info['username'])) {
        $str['code'] = 401;
        $str['msg'] = "账号不符合规则！只能包括字母数字下划线！";
    } elseif (!preg_match('/^.{6,20}$/', $info['username'])) {
        $str['code'] = 402;
        $str['msg'] = "账号长度最短为六位，最长为20位！";
    } elseif ($info['password'] == null) {
        $str['code'] = 410;
        $str['msg'] = "密码为空";
    } else {
        $str['code'] = 200;
        $str['msg'] = "验证通过";
    }

    if ($str['code'] != 200) {
        echo json_encode($str, 256);
        exit();
    }
}

function checkLogin($info)
{
    $str = array
    (
        'code' => '',
        'msg' => '',
    );
    if (selectUsername($info)) {
        if (selectUsernameAndPassword($info)) {
            $str['code'] = 200;
            $str['msg'] = "验证成功";
        } else {
            $str['code'] = 420;
            $str['msg'] = "密码不正确";
        }
    } else {
        $str['code'] = 421;
        $str['msg'] = "账号不存在";
    }

    if ($str['code'] != 200) {
        echo json_encode($str, 256);
        return false;
    } else {
        return true;
    }

}

function selectUsername($info)
{

    $conn = new Connection();
    $sql = "select * from forum.forum_user where username = ? ";
    $stmt = $conn->mysqli->prepare($sql);
    $stmt->bind_param("s", $info['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function selectUsernameAndPassword($info)
{

    $conn = new Connection();
    $sql = "select * from forum.forum_user where username= ? and password= ? ";
    $stmt = $conn->mysqli->prepare($sql);
    $stmt->bind_param("ss", $info['username'], $info['password']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}