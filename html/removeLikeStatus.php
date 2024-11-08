<?php
session_start();

$DBHost = "localhost";
$DBUser = "root";
$DBPass = "";
$DBName = "artourdb";
$conn = mysqli_connect($DBHost, $DBUser, $DBPass, $DBName);

$imageId = $_GET['unlikePostId'];
$profileId = $_SESSION['userId'];

$query = "DELETE FROM likes WHERE imageId=$imageId AND profileId=$profileId";
$unlike = mysqli_query ($conn, $query);
header("location:viewpost.php?post=".$imageId);
die();
?>