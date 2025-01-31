<?php
session_start();

$DBHost = "localhost";
$DBUser = "root";
$DBPass = "";
$DBName = "artourdb";
$conn = mysqli_connect($DBHost, $DBUser, $DBPass, $DBName);

$imageId = $_GET['unlikePostId'];
$profileId = $_SESSION['userId'];

$getUploader = "SELECT userId FROM images WHERE imageId=$imageId";
$uploaderResult = mysqli_query ($conn, $getUploader);
$account = mysqli_fetch_assoc($uploaderResult);
$notified = $account['userId'];

$query = "DELETE FROM likes WHERE imageId=$imageId AND profileId=$profileId";
$unlike = mysqli_query ($conn, $query);

$notifQuery = "INSERT INTO notifications (notifierId, notifType, imageId, notifiedId, notifyDate, readStatus)
VALUES ($profileId, 11, $imageId, $notified, now(), 0)";
$notif = mysqli_query ($conn, $notifQuery);

$deleteOldNotifQuery = "DELETE FROM notifications WHERE notifierId=$profileId AND notifiedId=$notified AND notifType=1 AND imageId=$imageId";
$deleteNotif = mysqli_query ($conn, $deleteOldNotifQuery);

header("location:viewpost.php?post=".$imageId);
die();
?>