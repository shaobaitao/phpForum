<?php
require 'component/MysqlConnection.php';

$conn = new Connection();
$viewsW = 80;
$likesW = 4;
$commentsW = 1;
$sql = "SELECT COUNT(*) FROM forum.post";

$stmt = $conn->mysqli->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode($row['COUNT(*)']);