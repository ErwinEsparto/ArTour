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

        $getImages = "SELECT * FROM images WHERE userId='$_GET[profile]' ORDER BY uploadDate DESC";
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
                <img src="../images/searchicon.png" alt="">
                <input type="search" id="search" name="search" placeholder="Search">
            </div>
        
            <div class="navigation">
                <nav class="sections">
                    <?php
                        if ($loggedIn === true){
                            echo '  
                                <a href="uploadimage.php"> Upload </a>
                                <a href="chats.php"> Chats </a>
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
                        
                        if ($followStatus==1) {
                            echo"
                                <a class='followOn' href='removeFollowStatus.php?followId=".$_GET['profile']."'>
                                    <p> Following </p>
                                </a>
                            ";
                        }
                        else {
                            echo"
                                <a class='followOff' href='addFollowStatus.php?followId=".$_GET['profile']."'>
                                    <p> Follow </p>
                                </a>";
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
    </main>
</body>
</html>