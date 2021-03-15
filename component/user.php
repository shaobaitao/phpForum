<?php
//login
function updateLogin($info)
{

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

function checkLoginInfo($info)
{

    if ($info['username'] == null) {
        statusCode(400, "账号为空");
        exit();
    } elseif (!preg_match('/^\w+$/', $info['username'])) {
        statusCode(401, "账号不符合规则！只能包括字母数字下划线！");
        exit();
    } elseif (!preg_match('/^.{6,20}$/', $info['username'])) {
        statusCode(402, "账号长度最短为六位，最长为20位！");
        exit();
    } elseif ($info['password'] == null) {
        statusCode(410, "密码为空");
        exit();
    } elseif (strlen($info['password']) != 32) {
        statusCode(411, "密码长度不符合要求");
        exit();
    }
}

function checkLogin($info)
{
    if (selectUsername($info)) {
        if (selectUsernameAndPassword($info)) {
            statusCode(200, "验证成功");

        } else {
            statusCode(420, "密码不正确");
            exit();
        }
    } else {
        statusCode(421, "账号不存在");
        exit();
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

//register
function getRegisterInfo()
{
    $info = array(
        'username' => '',
        'password' => '',
        'email' => ''
    );
    $data = file_get_contents("php://input");
    $data = json_decode($data, true);

    $info['username'] = trim($data['name']);
    $info['password'] = trim($data['pass']);
    $info['email'] = trim($data['email']);

    return $info;
}

function checkRegisterInfo($info)
{
    if ($info['username'] == null) {
        statusCode(400, "账号为空");
        exit();
    } elseif (!preg_match('/^\w+$/', $info['username'])) {
        statusCode(401, "账号不符合规则！只能包括字母数字下划线！");
        exit();
    } elseif (!preg_match('/^.{6,20}$/', $info['username'])) {
        statusCode(402, "账号长度最短为六位，最长为20位！");
        exit();
    } elseif (selectUsername($info)) {
        statusCode(403, "账号已注册，请换个名字！");
        exit();
    } elseif ($info['password'] == null) {
        statusCode(410, "密码为空");
        exit();
    } elseif (strlen($info['password']) != 32) {
        statusCode(411, "密码长度不符合要求");
        exit();
    } elseif (!preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $info['email'])) {
        statusCode(420, "邮箱格式不符合要求");
        exit();
    }
}

function register($info)
{
    $conn = new Connection();

    $sql = "INSERT INTO forum.forum_user VALUES (null, ? , ? , ? ,null, ? ,0,1,0);";
    $stmt = $conn->mysqli->prepare($sql);
    $now = date('Y-m-d H:i:s', time());
    $stmt->bind_param("ssss", $info['username'], $info['password'], $info['email'], $now);
    if ($stmt->execute()) {
        statusCode(200, '注册成功');
    } else {
        statusCode(450, '注册失败');
    }

//    $sql = "INSERT INTO forum.forum_userInfo VALUES (null, ? , null , null ,null, null ,null,null,null);";
    $userID=getID($info['username']);


    $avatar='http://forum.shaobaitao.cn/avatar/nut.png';
    $sql = "insert into forum.forum_userInfo values ( null , ? , null , null , null , null , ? , null , null ) ";
    $stmt = $conn->mysqli->prepare($sql);
    $stmt->bind_param("is", $userID,$avatar);
    $stmt->execute();

}

function statusCode($code, $msg)
{
    $str = array
    (
        'code' => $code,
        'msg' => $msg,
    );
    echo json_encode($str, 256);
}

function getUsername($id){
    $conn = new Connection();
    $sql = "select username from forum.forum_user where id= ? ";
    $stmt = $conn->mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['username'];
}
function getID($name){
    $conn = new Connection();
    $sql = "select id from forum.forum_user where username= ? ";
    $stmt = $conn->mysqli->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['id'];
}