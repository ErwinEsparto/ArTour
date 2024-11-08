<?php
session_start();

$DBHost = "localhost";
$DBUser = "root";
$DBPass = "";
$DBName = "artourdb";
$conn = mysqli_connect($DBHost, $DBUser, $DBPass, $DBName);

$imageId = $_GET['likePostId'];
$profileId = $_SESSION['userId'];

$query = "INSERT INTO likes (imageId, likeStatus, profileId) VALUES ($imageId, 1, $profileId)";
$like = mysqli_query ($conn, $query);
header("location:viewpost.php?post=".$imageId);
die();
?>