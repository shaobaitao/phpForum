<?php
require 'component/MysqlConnection.php';
$conn=new Connection();
$calories = "Q'; DELETE FROM forum.car;";
$colour = 'red';
$sql="INSERT INTO forum.car VALUES (null, ? );";
echo 1;
$stmt=$conn->mysqli->prepare($sql);
$stmt->bind_param("s",$calories);
$stmt->execute();

