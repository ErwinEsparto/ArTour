<?php
        session_start();

        $DBHost = "localhost";
        $DBUser = "root";
        $DBPass = "";
        $DBName = "artourdb";
        $conn = mysqli_connect($DBHost, $DBUser, $DBPass, $DBName);
        $loggedIn = $_SESSION['loggedIn'] ?? false;

        $getUser = "SELECT * FROM users WHERE profileId='$_GET[profile]'";
        $result = mysqli_query($conn, $getUser);
        $account = mysqli_fetch_assoc($result);

        $getImages = "SELECT * FROM images WHERE userId='$_GET[profile]' AND deleteStatus!=1 ORDER BY uploadDate DESC";
        $imageResult = mysqli_query($conn, $getImages);

        $getFollowers = "SELECT * FROM follow 
        INNER JOIN users 
        ON follow.followedId=users.profileId
        WHERE followStatus=1 AND followedId='$_GET[profile]'";
        $followersResult = mysqli_query($conn, $getFollowers);
        $followers = mysqli_num_rows($followersResult);

        $getFollowing = "SELECT * FROM follow 
        INNER JOIN users 
        ON follow.followerId=users.profileId
        WHERE followStatus=1 AND followerId='$_GET[profile]'";
        $followingResult = mysqli_query($conn, $getFollowing);
        $following = mysqli_num_rows($followingResult);

        $sessionExpiration = 1800;
        if ($loggedIn==true){
            if (isset($_SESSION['latestActivity']) && (time() - $_SESSION['latestActivity']) > $sessionExpiration) {
                header("Location: logout.php");
                exit();
            }
            $_SESSION['latestActivity'] = time();
        }

        if(isset($_GET['unReportId'])){
            $unReportUser = "DELETE FROM reports WHERE reporterId='".$_SESSION['userId']."' AND reportedId='".$_GET['profile']."' AND reportType=2";
            $unReportResult = mysqli_query ($conn, $unReportUser);
            header("Location: viewprofile.php?profile=" . $_GET['unReportId']);
            die();
        }
        if(isset($_GET['profile'])){
            $existProifle = "SELECT * FROM users WHERE profileId='$_GET[profile]'";
            $existProfileResult = mysqli_query($conn, $existProifle);
            $isProfileExist = mysqli_num_rows($existProfileResult);
            if ($isProfileExist>0){
                $profile = mysqli_fetch_assoc($existProfileResult);
                if($profile['profileId']!=$_SESSION['userId']){

                }
                else {
                    header("location:profile.php");
                    die();
                }
            }
            else {
                header("location:home.php");
                die();
            }
        }
        if(isset($_GET['notifId'])){
            $updateNotif = "UPDATE notifications SET readStatus=1 WHERE notificationId='".$_GET['notifId']."'";
            $notifUpdate = mysqli_query($conn, $updateNotif);
        }
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $account['profileName']; ?></title>
    <link rel="stylesheet" href="../css/viewprofile.css">
</head>
<body>

    

    <header>
        <div class="mainheader">
            <div class="logo">
                <h1><a href="home.php"> ArTour </a></h1>
            </div>

            <div class="search">
                <form action="search.php" method="POST">
                    <img src="../images/searchicon.png" alt="">
                    <input type="search" id="search" name="search" placeholder="Search">
                    <button type="submit" style="display:none;">Submit</button>
                </form>
            </div>
        
            <div class="navigation">
                <nav class="sections">
                    <?php
                        if ($loggedIn === true && $_SESSION['userType']==2){
                            echo '  
                                <a href="uploadimage.php"> Upload </a>
                                <a href="notifications.php"> Notifications </a>
                                <a href="chats.php"> Chats </a>
                                <a href="home.php"> Home </a>
                                <a class="button" href="logout.php"> Logout </a>
                            ';
                        }
                        else if ($loggedIn == true && $_SESSION['userType']==1){
                            echo '  
                                <a href="reports.php"> Reports </a>
                                <a href="userManage.php"> Accounts </a>
                                <a href="home.php"> Home </a>
                                <a class="button" href="logout.php"> Logout </a>
                            ';
                        }
                        else {
                            echo '
                                <a href="login.php"> Upload an Image </a>
                                <a href="home.php"> Home </a>
                                <a class="button" href="login.php"> Login </a>
                            ';
                        }
                    ?>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <div class="profile">
            <div class="picture">
                <img src="../profiles/<?php echo $account['profilePicture']; ?>"  alt="">
            </div>
            <div class="personalInfo">
                <div class="header">
                    <h1> <?php echo $account['profileName']; ?> </h1>

                    <?php
                    if ($loggedIn === true){
                        $getFollowStatus = "SELECT * FROM follow WHERE followerId='".$_SESSION['userId']."' AND followedId='$_GET[profile]' AND followStatus=1";
                        $followStatusResult = mysqli_query($conn, $getFollowStatus);
                        $followStatus = mysqli_num_rows($followStatusResult);
                        
                        if ($_SESSION['userType']==1){
                            echo '';
                        }
                        else if ($followStatus==1) {
                            echo"
                                <div class='actions'>
                                <a class='followOn' href='removeFollowStatus.php?followId=".$_GET['profile']."'>
                                    <p> Following </p>
                                </a>
                            ";
                        }
                        else {
                            echo"
                                <div class='actions'>
                                <a class='followOff' href='addFollowStatus.php?followId=".$_GET['profile']."'>
                                    <p> Follow </p>
                                </a>";
                        }

                        $checkReport = "SELECT * FROM reports WHERE reporterId='".$_SESSION['userId']."' AND reportedId='".$_GET['profile']."' AND reportType=2";
                        $checkReportResult = mysqli_query($conn, $checkReport);
                        $checkReportRow = mysqli_num_rows($checkReportResult);

                        if ($_SESSION['userType']==1){
                            echo '';
                        }
                        else if ($checkReportRow==0) {
                            echo"
                                <a class='followOff' href='#divOne'>
                                    <p> Report </p>
                                </a>
                                </div>
                            ";
                        }
                        else {
                            echo"
                                <a class='followOn' href='viewprofile.php?profile=".$_GET['profile']."&&unReportId=".$_GET['profile']."'>
                                    <p> Reported </p>
                                </a>
                                </div>";
                        }
                    }
                    else{
                        echo "";
                    }
                    ?>
                </div>
                <div class="follow">
                    <p> <b> <?php echo $following; ?> </b> Following </p> | 
                    <p> <b> <?php echo $followers; ?> </b> Followers </p>
                </div>
                <p class="description"> <?php echo $account['profileDescription']; ?> </p>
                <div class="address">
                    <img src="../images/location.png" alt="">
                    <p> <?php echo $account['profileAddress']; ?> </p>
                </div>
                <div class="email">
                    <img src="../images/email.png" alt="">
                    <p> <?php echo $account['profileEmail']; ?> </p>
                </div>
                <div class="contact">
                    <img src="../images/contact.png" alt="">
                    <p> <?php echo $account['profileNumber']; ?> </p>
                </div>
            </div>
            <div class="socials">
                <div class="title">
                    <h1> Socials </h1>
                </div>
                <div class="facebook">
                    <img src="../images/facebook.png" alt="">
                    <p> <?php echo $account['profileFacebook']; ?> </p>
                </div>
                <div class="instagram">
                    <img src="../images/instagram.png" alt="">
                    <p> <?php echo $account['profileInstagram']; ?> </p>
                </div>
                <div class="x">
                    <img src="../images/x.png" alt="">
                    <p> <?php echo $account['profileX']; ?> </p>
                </div>
            </div>
        </div>
        <div class="gallery">
            <a class="active" href="#"> Uploaded Photos </a>
        </div>
        <div class="container">
            <?php
                $images = mysqli_num_rows($imageResult);

                if($images>0){
                    while ($image = mysqli_fetch_assoc($imageResult)){
                        $getCategories = "SELECT * FROM categories WHERE imageId='".$image['imageId']."'";
                        $categoriesResult = mysqli_query($conn, $getCategories);
                        $categoryCollection = mysqli_num_rows($categoriesResult);
                        
                        $uploadDate = strtotime($image['uploadDate']);
                        $formatDate = date("m/d/y g:i A", $uploadDate);
                        echo "
                        <div class='post'> 
                            <a class='viewpost' href='viewpost.php?post=".$image['imageId']."'>
                            <img src='../posts/".$image['imageName']."'/> 
                            <div class='uploader'>
                                <div class='details'>
                                    <p> ".$formatDate." </p>
                                </div>
                                <div class='postCategories'>";
                                    if($categoryCollection>0){
                                        while ($category = mysqli_fetch_assoc($categoriesResult)){
                                            if($category['category']!='None'){
                                                echo " <p> ".$category['category']." </p> ";
                                            }
                                        }
                                    }
                        echo"   </div>
                            </div>
                            </a>
                        </div>";
                    }
                }
            ?>
        </div>
        <div class="overlay" id="divOne">
            <div class="wrapper">
                <h2>Report Details</h2><a class="close" href="#">&times;</a>
                <div class="content">
                    <div class="form-container">
                        <form method="POST" enctype="multipart/form-data">
                            <label>Reason</label> 
                            <textarea name="reason" maxlength='30' required placeholder="Reason for reporting"></textarea>
                            <input type="submit" name="submit" value="Submit">
                            <?php
                                if(isset($_POST["submit"])){
                                    $reason = htmlspecialchars(trim($_POST['reason'])); 
                                    $reported = $_GET['profile'];
                                    if(!empty($reason)){
                                        $recordReport = "INSERT INTO reports (reportedId, reportReason, reportType, reporterId, reportDate) 
                                        VALUES ('".$_GET['profile']."', '$reason', 2, '".$_SESSION['userId']."', now())";
                                        $reportQuery = mysqli_query($conn, $recordReport);
                                        echo "<p class='success'> User reported successfully. </p>";
                                    }
                                }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>