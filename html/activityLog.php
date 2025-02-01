<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activities Log</title>
    <link rel="stylesheet" href="../css/activityLog.css">
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

        $getActivities ="SELECT * FROM notifications WHERE notifierId='".$_SESSION['userId']."' ORDER BY notifyDate DESC";
        $activitiesResult = mysqli_query($conn, $getActivities);
        $activitiesRow = mysqli_num_rows($activitiesResult);

        $sessionExpiration = 1800;
        if ($loggedIn==true){
            if (isset($_SESSION['latestActivity']) && (time() - $_SESSION['latestActivity']) > $sessionExpiration) {
                header("Location: logout.php");
                exit();
            }
            $_SESSION['latestActivity'] = time();
        }

        if($_SESSION['userType']!=2){
            header("location: home.php");
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
                            <a href="notifications.php"> Notifications </a>
                            <a href="chats.php"> Chats </a>
                            <a href="profile.php"> Profile </a>
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
        <div class="activitiesSection">
        <h1> Activity Log </h1>
        <?php
            if ($activitiesRow>0){
                while ($activity = mysqli_fetch_assoc($activitiesResult)){
                    $getNotified = "SELECT * FROM users WHERE profileId='".$activity['notifiedId']."'";
                    $notifiedResult = mysqli_query($conn, $getNotified);
                    $notified = mysqli_fetch_assoc($notifiedResult);

                    $getNotifier = "SELECT * FROM users WHERE profileId='".$activity['notifierId']."'";
                    $notifierResult = mysqli_query($conn, $getNotifier);
                    $notifier = mysqli_fetch_assoc($notifierResult);

                    $notifDate = strtotime($activity['notifyDate']);
                    $formatDate = date("M j, Y g:i A", $notifDate);

                    if ($activity['notifType']==1){
                        echo "
                        <a class='activities' target='_blank' href='viewpost.php?post=".$activity['imageId']."'>
                            <img src='../profiles/".$notifier['profilePicture']."'/>
                            <p> You liked a post from ".$notified['profileName'].". <br> <span style='color: gray;'> $formatDate. </span> </p> 
                            <img src='../profiles/".$notified['profilePicture']."'/>
                        </a>
                        ";
                    }
                    else if ($activity['notifType']==2){
                        echo "
                        <a class='activities' target='_blank' href='viewpost.php?post=".$activity['imageId']."'>
                            <img src='../profiles/".$notifier['profilePicture']."'/>
                            <p> You commented on a post from ".$notified['profileName'].". <br> <span style='color: gray;'> $formatDate. </span> </p> 
                            <img src='../profiles/".$notified['profilePicture']."'/>
                        </a>
                        ";
                    }
                    else if ($activity['notifType']==3){
                        echo "
                        <a class='activities' target='_blank' href='viewprofile.php?profile=".$notified['profileId']."'>
                            <img src='../profiles/".$notifier['profilePicture']."'/>
                            <p> You followed ".$notified['profileName'].". <br><span style='color: gray;'> $formatDate. </span></p> 
                            <img src='../profiles/".$notified['profilePicture']."'/>
                        </a>
                        ";
                    }
                    else if ($activity['notifType']==4){
                        echo "
                        <a class='activities' target='_blank' href='viewprofile.php?profile=".$notified['profileId']."'>
                            <img src='../profiles/".$notifier['profilePicture']."'/>
                            <p> You messaged ".$notified['profileName'].". <br><span style='color: gray;'> $formatDate. </span></p> 
                            <img src='../profiles/".$notified['profilePicture']."'/>
                        </a>
                        ";
                    }
                    else if ($activity['notifType']==5){
                        echo "
                        <a class='activities' href='#'>
                            ASD
                        </a>
                        ";
                    }
                    else if ($activity['notifType']==6){
                        echo "
                        <a class='activities' href='profile.php'>
                            <img src='../profiles/".$notifier['profilePicture']."'/>
                            <p> You have updated your profile picture. <br><span style='color: gray;'> $formatDate. </span></p> 
                        </a>
                        ";
                    }
                    else if ($activity['notifType']==7){
                        echo "
                        <a class='activities' target='_blank' href='viewpost.php?post=".$activity['imageId']."'>
                            <img src='../profiles/".$notifier['profilePicture']."'/>
                            <p> You have edited your post. <br><span style='color: gray;'> $formatDate. </span></p> 
                        </a>
                        ";
                    }
                    else if ($activity['notifType']==8){
                        echo "
                        <a class='activities' href='#'>
                            <img src='../profiles/".$notifier['profilePicture']."'/>
                            <p> You have recovered your password. <br><span style='color: gray;'> $formatDate. </span></p> 
                        </a>
                        ";
                    }
                    else if ($activity['notifType']==9){
                        echo "
                        <a class='activities' href='#'>
                            <img src='../profiles/".$notifier['profilePicture']."'/>
                            <p> You have logged in at: <span style='color: gray;'> $formatDate. </span> </p> 
                        </a>
                        ";
                    }
                    else if ($activity['notifType']==10){
                        echo "
                        <a class='activities' target='_blank' href='viewprofile.php?profile=".$notified['profileId']."'>
                            <img src='../profiles/".$notifier['profilePicture']."'/>
                            <p> You unfollowed ".$notified['profileName'].". <br><span style='color: gray;'> $formatDate. </span></p> 
                            <img src='../profiles/".$notified['profilePicture']."'/>
                        </a>
                        ";
                    }
                    else if ($activity['notifType']==11){
                        echo "
                        <a class='activities' target='_blank' href='viewpost.php?post=".$activity['imageId']."'>
                            <img src='../profiles/".$notifier['profilePicture']."'/>
                            <p> You unliked a post from ".$notified['profileName'].". <br><span style='color: gray;'> $formatDate. </span></p> 
                            <img src='../profiles/".$notified['profilePicture']."'/>
                        </a>
                        ";
                    }
                    else if ($activity['notifType']==12){
                        echo "
                        <a class='activities' target='_blank' href='viewpost.php?post=".$activity['imageId']."'>
                            <img src='../profiles/".$notifier['profilePicture']."'/>
                            <p> You have uploaded a photo. <br><span style='color: gray;'> $formatDate. </span></p> 
                        </a>
                        ";
                    }
                    else if ($activity['notifType']==13){
                        echo "
                        <a class='activities' href='#'>
                            <img src='../profiles/".$notifier['profilePicture']."'/>
                            <p> You have deleted one of your posts. <br><span style='color: gray;'> $formatDate. </span></p> 
                        </a>
                        ";
                    }
                    else if ($activity['notifType']==15){
                        echo "
                        <a class='activities' href='profile.php'>
                            <img src='../profiles/".$notifier['profilePicture']."'/>
                            <p> You have updated your profile details. <br> <span style='color: gray;'> $formatDate. </span> </p> 
                        </a>
                        ";
                    }
                }
            }
            else {
                echo "
                <p class='noActivities'>
                    No Activities Found.
                </p>";
            }
        ?>
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
</body>
</html>