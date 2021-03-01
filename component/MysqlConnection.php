<?php
// 创建连接
//$conn = mysqli_connect("101.37.17.251", "forum", "forum", "forum");
//if (!$conn) {
//    die("连接失败: " . mysqli_connect_error());
//}
define('SERVER', '101.37.17.251');
define('USER', 'forum');
define('PASS', 'forum');
define('DB', 'forum');
class Connection{
    var $mysqli = null;
    function __construct(){
        try{
            if(!$this->mysqli){
                $this->mysqli = new MySQLi(SERVER, USER, PASS, DB);
                if(!$this->mysqli)
                    throw new Exception('Could not create connection using MySQLi', 'NO_CONNECTION');
            }
        }
        catch(Exception $e){
            echo "ERROR: ".$e->getMessage();
        }
    }
}