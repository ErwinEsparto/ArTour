<?php
session_start();

$DBHost = "localhost";
$DBUser = "root";
$DBPass = "";
$DBName = "artourdb";
$conn = mysqli_connect($DBHost, $DBUser, $DBPass, $DBName);

$postId = $_GET['reportId'];

$query = "UPDATE images SET reportStatus=0 WHERE imageId=$postId";
$follow = mysqli_query ($conn, $query);
header("location:".$_SERVER['HTTP_REFERER']);
die();
?>