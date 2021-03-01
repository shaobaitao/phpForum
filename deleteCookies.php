<?php
require 'component/MysqlConnection.php';
$conn=new Connection();
$calories = "sbt123";
$colour = 'red';
//$sql="INSERT INTO forum.forum_user VALUES (null, ? ,'123456','noob@shaobaitao.cn','2021-02-24 15:31:08','2021-02-24 15:31:08',0,1,0);";
$sql="select * from forum.forum_user ";
echo 1;
$stmt=$conn->mysqli->prepare($sql);
//$stmt->bind_param("s",$calories);

$res=$stmt->execute();

$result = $stmt->get_result();   // You get a result object now
if($result->num_rows > 0) {     // Note: change to $result->...!
    while ($data = $result->fetch_assoc()) {
        echo $data['username'].'/'.$data['password'];
    }
}
echo $res;
