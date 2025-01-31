<?php
session_start();

$DBHost = "localhost";
$DBUser = "root";
$DBPass = "";
$DBName = "artourdb";
$conn = mysqli_connect($DBHost, $DBUser, $DBPass, $DBName);

$followedId = $_GET['followId'];
$followerId = $_SESSION['userId'];

$query = "INSERT INTO follow (followerId, followStatus, followedId) VALUES ($followerId, 1, $followedId)";
$follow = mysqli_query ($conn, $query);

$notifQuery = "INSERT INTO notifications (notifierId, notifType, notifiedId, notifyDate, readStatus)
VALUES ($followerId, 3, $followedId, now(), 0)";
$notif = mysqli_query ($conn, $notifQuery);

$deleteOldNotifQuery = "DELETE FROM notifications WHERE notifierId=$followerId AND notifiedId=$followedId AND notifType=10";
$deleteNotif = mysqli_query ($conn, $deleteOldNotifQuery);

header("location:".$_SERVER['HTTP_REFERER']);
die();
?>