<?php

$info = getUserInfo();
setCookies($info);
checkInfo($info);


function setCookies($info){
    if($info['isSetCookies']){
        setcookie("userInfoName", $info['username'], time()+60*60*24*30);
        setcookie("userInfoPass", $info['password'], time()+60*60*24*30);
    }
}
function getUserInfo(){
    $info=array(
        'username'=>'',
        'password'=>'',
        'isSetCookies'=>false
    );
    if(isset($_COOKIE['userInfoName'])){
        $info['username']=$_COOKIE["userInfoName"];
        $info['password']=$_COOKIE["userInfoPass"];
    }
    else{
        $data = file_get_contents("php://input");
        $data = json_decode($data, true);

        $info['username'] = trim($data['name']);
        $info['password'] = trim($data['pass']);
        $info['isSetCookies'] = $data['isSetCookies'];
    }
    return $info;
}
function checkInfo($info){
    $str = array
    (
        'code'=> '',
        'msg' => '',
    );
    if($info['username']==null){
        $str['code']=400;
        $str['msg']="账号为空";
    }
    elseif (!preg_match('/^\w+$/',$info['username'])){
        $str['code']=401;
        $str['msg']="账号不符合规则！只能包括字母数字下划线！";
    }
    elseif (!preg_match('/^.{6,20}$/',$info['username'])){
        $str['code']=402;
        $str['msg']="账号长度最短为六位，最长为20位！";
    }
    elseif ($info['password']==null){
        $str['code']=410;
        $str['msg']="密码为空";
    }
    elseif (!preg_match('/^.{6,20}$/',$info['password'])){
        $str['code']=412;
        $str['msg']="密码长度最短为六位，最长为20位！";
    }
    else{
        $str['code']=200;
        $str['msg']="验证通过";
    }

    if($str['code']!=200){
        echo json_encode($str, 256);
        exit();
    }
}