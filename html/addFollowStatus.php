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
header("location:".$_SERVER['HTTP_REFERER']);
die();
?>