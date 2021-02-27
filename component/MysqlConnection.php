<?php
// 创建连接
$conn = mysqli_connect("101.37.17.251", "forum", "forum", "forum");
if (!$conn) {
    die("连接失败: " . mysqli_connect_error());
}