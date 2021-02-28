<?php

$info = getUserInfo();
setCookies($info);



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