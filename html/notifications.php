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

        $getLikesNotifications = "SELECT * FROM notifications JOIN images ON images.imageId=notifications.imageId JOIN likes on likes.imageId=images.imageId
        WHERE notifications.notifiedId='".$_SESSION['userId']."' AND notifications.notifType=1 AND likes.likeStatus=1 AND images.deleteStatus!=1 AND images.userId='".$_SESSION['userId']."'
        AND notifications.notifType!=11
        ORDER BY notifications.notifyDate DESC";
        $notificationsLikesResult = mysqli_query($conn, $getLikesNotifications);
        $notificationLikesRow = mysqli_num_rows($notificationsLikesResult);

        $getCommentsNotifications = "SELECT * FROM notifications JOIN images ON images.imageId=notifications.imageId JOIN comments ON images.imageId=comments.postId 
        WHERE images.userId='".$_SESSION['userId']."' AND notifications.notifiedId='".$_SESSION['userId']."' AND notifications.notifType=2 AND images.deleteStatus!=1 
        ORDER BY notifications.notifyDate DESC";
        $notificationCommentsResult = mysqli_query($conn, $getCommentsNotifications);
        $notificationCommentsRow = mysqli_num_rows($notificationCommentsResult);

        $getFollowsNotifications = "SELECT * FROM notifications WHERE notifications.notifiedId='".$_SESSION['userId']."' 
        AND notifications.notifType=3 AND notifications.notifType!=10 ORDER BY notifications.notifyDate DESC";
        $notificationFollowsResult = mysqli_query($conn, $getFollowsNotifications);
        $notificationFollowsRow = mysqli_num_rows($notificationFollowsResult);

        if(isset($_GET['notifId'])){
            $deleteNotif = "DELETE FROM notifications WHERE notificationId='".$_GET['notifId']."'";
            $deleteResult = mysqli_query($conn, $deleteNotif);
            header("Location: notifications.php");
            die();
        }

        if(isset($_SESSION['userId']) && $_SESSION['userType']!=1){
            echo"";
        }
        else {
            header("location:home.php");
            die();
        }
    ?>

    <header>
        <div class="mainheader">
            <div class="logo">
                <h1> ArTour </h1>
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
                    if ($loggedIn == true && $_SESSION['userType']==2){
                        echo '  
                            <a href="uploadimage.php"> Upload </a>
                            <a class="active" href="notifications.php"> Notifications </a>
                            <a href="chats.php"> Chats </a>
                            <a href="home.php"> Home </a>
                            <a class="button" href="#divOne"> Logout </a>
                        ';
                    }
                    else if ($loggedIn == true && $_SESSION['userType']==1){
                        echo '  
                            <a href="reports.php"> Reports </a>
                            <a href="userManage.php"> Accounts </a>
                            <a class="button" href="#divOne"> Logout </a>
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
        <?php
            $getReportStatus = "SELECT * FROM reports WHERE reportedId='".$_SESSION['userId']."'";
            $reportStatusResult = mysqli_query($conn, $getReportStatus);
            $checkReportRow = mysqli_num_rows($reportStatusResult);
            $reportStatus = mysqli_fetch_assoc ($reportStatusResult);
            if ($checkReportRow==0){
                echo "";
            }
            else if ($checkReportRow>1){
                echo "<p class='reported'> You have been reported to the Admin for '".$reportStatus['reportReason']."' and more. Email us at artour@gmail.com to explain your case. </p>";
            }
            else {
                echo "<p class='reported'> You have been reported to the Admin for '".$reportStatus['reportReason']."'. Email us at artour@gmail.com to explain your case. </p>";
            }
        ?>
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
                    if($notificationLikesRow>0){
                        while ($like = mysqli_fetch_assoc($notificationsLikesResult)){
                            $getNotifier = "SELECT * FROM users WHERE profileId='".$like['profileId']."'";
                            $notifierResult = mysqli_query($conn, $getNotifier);
                            $notifier = mysqli_fetch_assoc($notifierResult);

                            $likeDate = strtotime($like['notifyDate']);
                            $formatDate = date("M j, Y g:i A", $likeDate);

                            echo"
                            <div class='detailsSection'>
                                <a class='deleteNotif' href='javascript:void()' onClick='delAlert(".$like['notificationId'].")'> ... </a>
                                <a class='like-notification' target='_blank' href='viewpost.php?post=".$like['imageId']."&&notifId=".$like['notificationId']."'>
                                    <img class='notifier-profile' src='../profiles/".$notifier['profilePicture']."'/> 
                                    <div class='notif-details'>
                                        <h2 class='notifier-name'> ".$notifier['profileName']." </h2>";
                                        if($like['readStatus']!=0){
                                            echo "<p class='notif-type'> likes your post. </p>";
                                        }
                                        else {
                                            echo "<p class='notif-type'><i><b> likes your post. </b></i></p>";
                                        }
                                        echo "<p class='date'> $formatDate </p>
                                    </div>
                                    <img class='notif-target' src='../posts/".$like['imageName']."'/> 
                                </a>
                            </div>
                            ";
                        }
                    }
                    else{
                        echo "<p class='no-notif'>No Notifications Available</p>";
                    }
                ?>
            </div>
            <div class="notification-bar">
                <?php
                    if($notificationCommentsRow>0){
                        while ($comment = mysqli_fetch_assoc($notificationCommentsResult)){
                            $getNotifier = "SELECT * FROM users WHERE profileId='".$comment['commentorId']."'";
                            $notifierResult = mysqli_query($conn, $getNotifier);
                            $notifier = mysqli_fetch_assoc($notifierResult);

                            $notifierComment = $comment['comment'];
                            $checkComment = (strlen($notifierComment) > 7) ? substr($notifierComment, 0, 7) . "..." : $notifierComment;

                            $commentDate = strtotime($comment['notifyDate']);
                            $formatDate = date("M j, Y g:i A", $commentDate);
                        
                            echo"
                            <div class='detailsSection'>
                                <a class='deleteNotif' href='javascript:void()' onClick='delAlert(".$comment['notificationId'].")'> ... </a>
                                <a class='like-notification' target='_blank' href='viewpost.php?post=".$comment['imageId']."&&notifId=".$comment['notificationId']."'>
                                    <img class='notifier-profile' src='../profiles/".$notifier['profilePicture']."'/> 
                                    <div class='notif-details commentWidth'>
                                        <h2 class='notifier-name'> ".$notifier['profileName']." </h2>";
                                        if($comment['readStatus']!=0){
                                            echo "<p class='notif-type'> commented '$checkComment' on your post. </p>";
                                        }
                                        else {
                                            echo "<p class='notif-type'><i><b> commented '$checkComment' on your post. </b></i></p>";
                                        }
                                        
                                        echo "<p class='date'> $formatDate </p>
                                    </div>
                                    <img class='notif-target commentImageWidth' src='../posts/".$comment['imageName']."'/> 
                                </a>
                            </div>";
                        }
                    }
                    else{
                        echo "<p class='no-notif'>No Notifications Available</p>";
                    }
                ?>
            </div>
            <div class="notification-bar">
                <?php
                    if($notificationFollowsRow>0){
                        while ($follow = mysqli_fetch_assoc($notificationFollowsResult)){
                            $getNotifier = "SELECT * FROM users WHERE profileId='".$follow['notifierId']."'";
                            $notifierResult = mysqli_query($conn, $getNotifier);
                            $notifier = mysqli_fetch_assoc($notifierResult);

                            $followDate = strtotime($follow['notifyDate']);
                            $formatDate = date("M j, Y g:i A", $followDate);

                            $checkFollowback = "SELECT * FROM follow WHERE followerId='".$_SESSION['userId']."' AND followedId='".$follow['notifierId']."'";
                            $followbackResult = mysqli_query($conn, $checkFollowback);
                            $followBackRow = mysqli_num_rows($followbackResult);

                            echo"
                            <div class='detailsSection'>
                                <a class='deleteNotif' href='javascript:void()' onClick='delAlert(".$follow['notificationId'].")'> ... </a>
                                <a class='like-notification' target='_blank' href='viewprofile.php?profile=".$follow['notifierId']."&&notifId=".$follow['notificationId']."'>
                                    <img class='notifier-profile' src='../profiles/".$notifier['profilePicture']."'/> 
                                    <div class='notif-details'>
                                        <h2 class='notifier-name'> ".$notifier['profileName']." </h2>";
                                        if ($followBackRow>0){
                                            if($follow['readStatus']!=0){
                                                echo "<p class='notif-type'> You now follow each other. </p>";
                                            }
                                            else {
                                                echo "<p class='notif-type'><i><b> You now follow each other. </b></i></p>";
                                            }
                                        }
                                        else {
                                            if($follow['readStatus']!=0){
                                                echo "<p class='notif-type'> has followed your account. Follow back? </p>";
                                            }
                                            else {
                                                echo "<p class='notif-type'><i><b> has followed your account. Follow back? </b></i></p>";
                                            }
                                            
                                        }
                                        
                                        echo"<p class='date'> $formatDate </p>
                                    </div>
                                </a>
                            </div>";
                        }
                    }
                    else{
                        echo "<p class='no-notif'>No Notifications Available</p>";
                    }
                ?>
            </div>
        </div>
        <div class="overlay" id="divOne">
            <div class="wrapper">
                <h2>Logout</h2><a class="close" href="#">&times;</a>
                <div class="content">
                    <div class="form-container">
                        <form method="POST" enctype="multipart/form-data">
                            <label>Are you sure you want to logout?</label> 
                            <a class='cancel' href="logout.php"> Logout </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        function delAlert(id){
            sts = confirm ("Are you sure you want to delete this notification?");
            if (sts){
                document.location.href=`notifications.php?notifId=${id}`;
            }
        }
    </script>
</body>
</html>