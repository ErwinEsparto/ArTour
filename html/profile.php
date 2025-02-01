<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../css/profile.css">
</head>
<body>

    <?php
        session_start();

        $DBHost = "localhost";
        $DBUser = "root";
        $DBPass = "";
        $DBName = "artourdb";
        $conn = mysqli_connect($DBHost, $DBUser, $DBPass, $DBName);

        $getUser = "SELECT * FROM users WHERE profileId='".$_SESSION['userId']."'";
        $result = mysqli_query($conn, $getUser);
        $account = mysqli_fetch_assoc($result);

        $getImages = "SELECT * FROM images WHERE userId='".$_SESSION['userId']."' AND deleteStatus!=1 ORDER BY uploadDate DESC";
        $imageResult = mysqli_query($conn, $getImages);

        $getFollowers = "SELECT * FROM follow 
        INNER JOIN users 
        ON follow.followedId=users.profileId
        WHERE followStatus=1 AND followedId='".$_SESSION['userId']."'";
        $followersResult = mysqli_query($conn, $getFollowers);
        $followers = mysqli_num_rows($followersResult);

        $getFollowing = "SELECT * FROM follow 
        INNER JOIN users 
        ON follow.followerId=users.profileId
        WHERE followStatus=1 AND followerId='".$_SESSION['userId']."'";
        $followingResult = mysqli_query($conn, $getFollowing);
        $following = mysqli_num_rows($followingResult);

        $loggedIn = $_SESSION['loggedIn'] ?? false;

        $sessionExpiration = 1800;
        if ($loggedIn==true){
            if (isset($_SESSION['latestActivity']) && (time() - $_SESSION['latestActivity']) > $sessionExpiration) {
                header("Location: logout.php");
                exit();
            }
            $_SESSION['latestActivity'] = time();
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
                    <a href="home.php"> Home </a>
                    <a class="button" href="#divOne"> Logout </a>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <div class="profile">
            <div class="picture">
                <img src="../profiles/<?php echo $account['profilePicture']; ?>"  alt="">
                <a href="changeprofile.php"> Change Profile Picture </a>
            </div>
            <div class="personalInfo">
                <div class="header">
                    <h1> <?php echo $account['profileName']; ?> </h1>
                    <div class="actions">
                        <a href="editprofile.php"> Edit Profile </a>
                        <a href="activityLog.php"> Logs </a>
                    </div>
                </div>
                <div class="follow">
                    <p><a href="following.php"> <b> <?php echo $following; ?> </b> Following </a> </p> | 
                    <p><a href="follower.php"> <b> <?php echo $followers; ?> </b> Followers  </a></p>
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
            <div class="link">
                <a class="active" href="#"> Uploaded </a>
            </div>
            <div class="link">
                <a href="likedimage.php"> Likes </a>
            </div>
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
                        $formatDate = date("M j, Y g:i A", $uploadDate);

                        $getTotalLikes = "SELECT * FROM likes WHERE likeStatus=1 AND imageId='".$image['imageId']."'";
                        $totalLikesResult = mysqli_query($conn, $getTotalLikes);
                        $totalLikes = mysqli_num_rows($totalLikesResult);

                        echo "
                        <div class='post'> 
                            <a class='viewpost' href='viewpost.php?post=".$image['imageId']."'>
                            <img src='../posts/".$image['imageName']."'/> 
                            <div class='uploader'>
                                <div class='details'>
                                    <p> Likes: ".$totalLikes." </p>
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