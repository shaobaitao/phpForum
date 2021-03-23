<?php
require 'component/MysqlConnection.php';
$data = file_get_contents("php://input");
$data = json_decode($data, true);

$info['num'] = trim($data['num']);
$info['type'] = trim($data['type']);
//hot new
switch ($info['type']) {
    case 'hot':
        $conn = new Connection();
        $viewsW = 80;
        $likesW = 4;
        $commentsW = 1;
        $sql = "select f.*,p.* from forum_userInfo f inner join post p on f.userID = p.authorID order by hot desc limit ? ";

        $stmt = $conn->mysqli->prepare($sql);
        $stmt->bind_param("i", $info['num']);
        $stmt->execute();
        $result = $stmt->get_result();
        $results = array();
        while ($row = $result->fetch_assoc()) {
            $item['nickname'] = $row['nickname'];
            $item['title'] = $row['title'];
            $item['content'] = $row['content'];
            $item['likes'] = $row['likes'];
            $item['views'] = $row['views'];
            $item['comments'] = $row['comments'];
            $item['createTime'] = $row['createTime'];
            $item['lastEditTime'] = $row['lastEditTime'];
            $item['headPortrait'] = $row['headPortrait'];
            $results[] = $item;
        }
        echo json_encode($results);
        break;

        break;
    case 'new':
        $conn = new Connection();
        $sql = "select f.*,p.* from forum_userInfo f inner join post p on f.userID = p.authorID order by lastEditTime desc limit ? ";
        $stmt = $conn->mysqli->prepare($sql);
        $stmt->bind_param("i", $info['num']);
        $stmt->execute();
        $result = $stmt->get_result();
        $results = array();
        while ($row = $result->fetch_assoc()) {
            $item['nickname'] = $row['nickname'];
            $item['title'] = $row['title'];
            $item['content'] = $row['content'];
            $item['likes'] = $row['likes'];
            $item['views'] = $row['views'];
            $item['comments'] = $row['comments'];
            $item['createTime'] = $row['createTime'];
            $item['lastEditTime'] = $row['lastEditTime'];
            $item['headPortrait'] = $row['headPortrait'];
            $results[] = $item;
        }
        echo json_encode($results);
        break;
}