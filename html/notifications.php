<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="../css/notifications.css">
</head>
<body>

    <?php
        session_start();

        $DBHost = "localhost";
        $DBUser = "root";
        $DBPass = "";
        $DBName = "artourdb";
        $conn = mysqli_connect($DBHost, $DBUser, $DBPass, $DBName);
        $loggedIn = $_SESSION['loggedIn'] ?? false;

        $getLikes = "SELECT * FROM images JOIN likes ON images.imageId=likes.imageId WHERE images.userId='".$_SESSION['userId']."' AND likes.likeStatus=1";
        $likesResult = mysqli_query($conn, $getLikes);
        $likesRow = mysqli_num_rows($likesResult);

        $getComments = "SELECT * FROM comments JOIN images ON images.imageId=comments.postId WHERE images.userId='".$_SESSION['userId']."'";
        $commentsResult = mysqli_query($conn, $getComments);
        $commentsRow = mysqli_num_rows($commentsResult);

        $getFollows = "SELECT * FROM follow WHERE followedId='".$_SESSION['userId']."'";
        $followsResult = mysqli_query($conn, $getFollows);
        $followsRow = mysqli_num_rows($followsResult);
    ?>

    <header>
        <div class="mainheader">
            <div class="logo">
                <h1> ArTour </h1>
            </div>

            <div class="search">
                <img src="../images/searchicon.png" alt="">
                <input type="search" id="search" name="search" placeholder="Search">
            </div>
        
            <div class="navigation">
                <nav class="sections">
                <?php
                    if ($loggedIn == true && $_SESSION['userType']==2){
                        echo '  
                            <a href="uploadimage.php"> Upload </a>
                            <a class="active" href="notifications.php"> Notifications </a>
                            <a href="chats.php"> Chats </a>
                            <a href="home.php"> Home </a>
                            <a class="button" href="logout.php"> Logout </a>
                        ';
                    }
                    else if ($loggedIn == true && $_SESSION['userType']==1){
                        echo '  
                            <a href="reports.php"> Reports </a>
                            <a class="button" href="logout.php"> Logout </a>
                        ';
                    }
                    else {
                        echo '
                            <a href="login.php"> Upload an Image </a>
                            <a class="button" href="login.php"> Login </a>
                        ';
                    }
                ?>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <div class="notification-sections">
            <div class="link">
                <a href="#"> Likes </a>
            </div>
            <div class="link">
                <a href="#"> Comments </a>
            </div>
            <div class="link">
                <a href="#"> Follows </a>
            </div>
        </div>
        <div class="all-notifications">
            <div class="notification-bar">
                <?php
                    if($likesRow>0){
                        while ($like = mysqli_fetch_assoc($likesResult)){
                            $getNotifier = "SELECT * FROM users WHERE profileId='".$like['profileId']."'";
                            $notifierResult = mysqli_query($conn, $getNotifier);
                            $notifier = mysqli_fetch_assoc($notifierResult);
                            echo"
                            <a class='like-notification' target='_blank' href='viewpost.php?post=".$like['imageId']."'>
                                <img class='notifier-profile' src='../profiles/".$notifier['profilePicture']."'/> 
                                <div class='notif-details'>
                                    <h2 class='notifier-name'> ".$notifier['profileName']." </h2>
                                    <p class='notif-type'> likes your post. </p>
                                </div>
                                <img class='notif-target' src='../posts/".$like['imageName']."'/> 
                            </a>";
                        }
                    }
                    else{
                        echo "<p class='no-notif'>No Notifications Available</p>";
                    }
                ?>
            </div>
            <div class="notification-bar">
                <?php
                    if($commentsRow>0){
                        while ($comment = mysqli_fetch_assoc($commentsResult)){
                            $getNotifier = "SELECT * FROM users WHERE profileId='".$comment['commentorId']."'";
                            $notifierResult = mysqli_query($conn, $getNotifier);
                            $notifier = mysqli_fetch_assoc($notifierResult);

                            $notifierComment = $comment['comment'];
                            $checkComment = (strlen($notifierComment) > 7) ? substr($notifierComment, 0, 7) . "..." : $notifierComment;

                            echo"
                            <a class='like-notification' target='_blank' href='viewpost.php?post=".$comment['imageId']."'>
                                <img class='notifier-profile' src='../profiles/".$notifier['profilePicture']."'/> 
                                <div class='notif-details'>
                                    <h2 class='notifier-name'> ".$notifier['profileName']." </h2>
                                    <p class='notif-type'> commented '$checkComment' on your post. </p>
                                </div>
                                <img class='notif-target' src='../posts/".$comment['imageName']."'/> 
                            </a>";
                        }
                    }
                    else{
                        echo "<p class='no-notif'>No Notifications Available</p>";
                    }
                ?>
            </div>
            <div class="notification-bar">
                <?php
                    if($followsRow>0){
                        while ($follow = mysqli_fetch_assoc($followsResult)){
                            $getNotifier = "SELECT * FROM users WHERE profileId='".$follow['followerId']."'";
                            $notifierResult = mysqli_query($conn, $getNotifier);
                            $notifier = mysqli_fetch_assoc($notifierResult);

                            echo"
                            <a class='like-notification' target='_blank' href='viewprofile.php?profile=".$follow['followerId']."'>
                                <img class='notifier-profile' src='../profiles/".$notifier['profilePicture']."'/> 
                                <div class='notif-details'>
                                    <h2 class='notifier-name'> ".$notifier['profileName']." </h2>
                                    <p class='notif-type'> has followed your account. Follow back? </p>
                                </div>
                            </a>";
                        }
                    }
                    else{
                        echo "<p class='no-notif'>No Notifications Available</p>";
                    }
                ?>
            </div>
        </div>
    </main>
</body>
</html>