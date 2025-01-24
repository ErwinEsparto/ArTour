<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Following</title>
    <link rel="stylesheet" href="../css/following.css">
</head>
<body>

    <?php
        session_start();

        $DBHost = "localhost";
        $DBUser = "root";
        $DBPass = "";
        $DBName = "artourdb";
        $conn = mysqli_connect($DBHost, $DBUser, $DBPass, $DBName);

        $getFollowing = "SELECT * FROM users 
        INNER JOIN follow 
        ON follow.followedId=users.profileId
        WHERE followStatus=1 AND followerId='".$_SESSION['userId']."'";
        $followingResult = mysqli_query($conn, $getFollowing);
        
    ?>

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
                    <a href="uploadimage.php"> Upload </a>
                    <a href="notifications.php"> Notifications </a>
                    <a href="chats.php"> Chats </a>
                    <a href="profile.php"> Profile </a>
                    <a class="button" href="logout.php"> Logout </a>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <div class="container">
            <h1> Following </h1>

            <?php
                $following = mysqli_num_rows($followingResult);

                if($following>0){
                    while ($followed = mysqli_fetch_assoc($followingResult)){
                        echo "
                        <div class='profile'>
                            <div class='details'>
                                <a class='viewprofile' href='viewprofile.php?profile=".$followed['profileId']."'><img src='../profiles/".$followed['profilePicture']."' alt=''></a>
                                <p> <a class='viewprofile' href='viewprofile.php?profile=".$followed['profileId']."'> ".$followed['profileName']." </a> </p>
                            </div>
                            <div class='followStatus'>";

                            if ($followed['followStatus']==1) {
                                echo"
                                    <a class='followOn' href='removeFollowStatus.php?followId=".$followed['profileId']."'>
                                        <p> Following </p>
                                    </a>
                                </div>
                        </div>
                                ";
                            }
                            else {
                                echo"
                                    <a class='followOff' href='addFollowStatus.php?followId=".$followed['profileId']."'>
                                        <p> Follow </p>
                                    </a>
                                </div>
                        </div>";
                                    
                            }
                    }
                }
            
            ?>
        </div>
    </main>
</body>
</html>