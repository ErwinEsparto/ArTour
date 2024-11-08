<?php
    session_start();

    $DBHost = "localhost";
    $DBUser = "root";
    $DBPass = "";
    $DBName = "artourdb";
    $conn = mysqli_connect($DBHost, $DBUser, $DBPass, $DBName);
    $loggedIn = $_SESSION['loggedIn'] ?? false;

    $getPost = "SELECT * FROM images WHERE imageId='$_GET[post]'";
    $postResult = mysqli_query($conn, $getPost);
    $post = mysqli_fetch_assoc($postResult);

    $getUser = "SELECT * FROM users 
        INNER JOIN images 
        ON images.userId=users.profileId
        WHERE imageId='$_GET[post]'";
    $userResult = mysqli_query($conn, $getUser);
    $uploader = mysqli_fetch_assoc($userResult);

    $getTotalLikes = "SELECT * FROM likes WHERE likeStatus=1 AND imageId='$_GET[post]'";
    $totalLikesResult = mysqli_query($conn, $getTotalLikes);
    $totalLikes = mysqli_num_rows($totalLikesResult);

    if(isset($_GET['deletePostId'])){
        $findImage = "SELECT * FROM images WHERE imageId='$_GET[deletePostId]' AND userId='".$_SESSION['userId']."'";
        $findResult = mysqli_query($conn, $findImage);
        $image = mysqli_fetch_assoc($findResult);

        $imageLocation = $_DIR_.'../posts/'.$image['imageName'];

        if(file_exists($imageLocation)){
            unlink($imageLocation);
            
            $query = "DELETE FROM images WHERE imageId='$_GET[deletePostId]' AND userId='".$_SESSION['userId']."'";
            $delete = mysqli_query ($conn, $query);

            $deleteCategory = "DELETE FROM categories WHERE imageId='$_GET[deletePostId]'";
            $deleteC = mysqli_query ($conn, $deleteCategory);

            $deleteLike = "DELETE FROM likes WHERE imageId='$_GET[deletePostId]'";
            $deleteL = mysqli_query ($conn, $deleteLike);

            header("location:profile.php");
            die();
        }
        else {
            echo "<script type='text/javascript'>alert('Image Not Found!');</script>";
            header("location:profile.php");
            die();
        }
    }
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php echo $uploader['profileName'] .' | '. $post['imageDescription']; ?> </title>
    <link rel="stylesheet" href="../css/viewpost.css">
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
                                <a href="uploadimage.php"> Upload an Image </a>
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
        <div class="post">
            <img src="../posts/<?php echo $post['imageName']; ?>" alt="">
            <p> <?php echo $post['imageDescription']; ?> </p>
        </div>
        <div class="details">

            <div class="uploader">
                <?php
                    if($loggedIn===false || $uploader['profileId'] != $_SESSION['userId']) {
                        echo "
                        <a class='viewpost' href='viewprofile.php?profile=".$uploader['profileId']."'><img src='../profiles/".$uploader['profilePicture']."' alt=''></a>
                        <div class='picture'>
                            <h1> <a class='viewpost' href='viewprofile.php?profile=".$uploader['profileId']."'> ".$uploader['profileName']." </a> </h1>";
                    }
                    else {
                        echo "
                        <a class='viewpost' href='profile.php'><img src='../profiles/".$uploader['profilePicture']."' alt=''></a>
                        <div class='picture'>
                            <h1> <a class='viewpost' href='profile.php'> ".$uploader['profileName']." </a> </h1>";
                    }
                ?>
                    <p> 
                        <?php 
                            $uploadDate = strtotime($uploader['uploadDate']);
                            $formatDate = date("m/d/y g:i A", $uploadDate);
                            echo "<p class='date'> $formatDate </p>"; 

                            $getCategories = "SELECT * FROM categories WHERE imageId='$_GET[post]'";
                            $categoriesResult = mysqli_query($conn, $getCategories);
                            $categoryCollection = mysqli_num_rows($categoriesResult);

                            echo "<div class='postCategories'>";
                                    if($categoryCollection>0){
                                        while ($category = mysqli_fetch_assoc($categoriesResult)){
                                            echo " <a class='cat' href='home.php?category=".$category['category']."'> <p> ".$category['category']." </p> </a>";
                                        }
                                    }
                            echo "</div>";
                        ?> 
                    </p>
                </div>  
            </div>

            <?php
                if($loggedIn===false) {
                    echo "";
                }

                else if ($uploader['profileId'] != $_SESSION['userId']){
                    $getLikeStatus = "SELECT * FROM likes WHERE profileId='".$_SESSION['userId']."' AND imageId='$_GET[post]'";
                    $likeStatusResult = mysqli_query($conn, $getLikeStatus);
                    $likeStatus = mysqli_num_rows($likeStatusResult);

                    $getFollowStatus = "SELECT * FROM follow WHERE followerId='".$_SESSION['userId']."' AND followedId='".$uploader['profileId']."' AND followStatus=1";
                    $followStatusResult = mysqli_query($conn, $getFollowStatus);
                    $followStatus = mysqli_num_rows($followStatusResult);

                    if ($likeStatus==1) {
                        echo "
                            <div class='actions'>
                                <a class='likeOn' href='removeLikeStatus.php?unlikePostId=".$_GET['post']."'>
                                    <img src='../images/likeOn.png'/>
                                    <p class='active'> Liked </p>
                                </a>";
                                if ($followStatus==1) {
                                    echo"
                                        <a class='followOn' href='removeFollowStatus.php?followId=".$uploader['profileId']."'>
                                            <img src='../images/followOn.png'/>
                                            <p> Following </p>
                                        </a>
                                    </div>
                                    ";
                                }
                                else {
                                    echo"
                                        <a class='followOff' href='addFollowStatus.php?followId=".$uploader['profileId']."'>
                                            <img src='../images/followOff.png'/>
                                            <p> Follow </p>
                                        </a>
                            </div>
                                ";
                                }
                    }
                    else {
                        echo "
                            <div class='actions'>
                                <a class='likeOff' href='addLikeStatus.php?likePostId=".$_GET['post']."'>
                                    <img src='../images/likeOff.png'/>
                                    <p class='active'> Like </p>
                                </a>";
                                if ($followStatus==1) {
                                    echo"
                                        <a class='followOn' href='removeFollowStatus.php?followId=".$uploader['profileId']."'>
                                            <img src='../images/followOn.png'/>
                                            <p> Following </p>
                                        </a>
                                    </div>
                                    ";
                                }
                                else {
                                    echo"
                                        <a class='followOff' href='addFollowStatus.php?followId=".$uploader['profileId']."'>
                                            <img src='../images/followOff.png'/>
                                            <p> Follow </p>
                                        </a>
                            </div>
                                ";
                                }
                    }
                }
                else {
                    echo "
                        <div class='actions ownerActions'>
                                <a class='likesTotal' href='#'>
                                        <img src='../images/likes.png'/>
                                        <p class='active'> ".$totalLikes." Likes </p>
                                </a>
                                <a class='editAction' href='editpost.php?postId=".$_GET['post']."'>
                                    <img src='../images/delete.png'/>
                                    <p class='followed'> Edit </p>
                                 </a>
                                 <a class='deleteAction' href='viewpost.php?deletePostId=".$_GET['post']."'>
                                    <img src='../images/delete.png'/>
                                    <p class='followed'> Delete </p>
                                 </a>
                        </div>
                    ";
                }
            ?>
        </div>
        <div class="otherPosts">
            <h1> <?php echo $uploader['profileName']; ?>'s Other Uploads</h1>
            <div class="container">
            <?php
                $getImages = "SELECT * FROM images 
                INNER JOIN users 
                ON images.userId=users.profileId
                WHERE userId='".$uploader['profileId']."'
                ORDER BY uploadDate DESC";
                $imageResult = mysqli_query($conn, $getImages);
                $images = mysqli_num_rows($imageResult);

                if($images>0){
                    while ($image = mysqli_fetch_assoc($imageResult)){
                        $getCategories = "SELECT * FROM categories WHERE imageId='".$image['imageId']."'";
                        $categoriesResult = mysqli_query($conn, $getCategories);
                        $categoryCollection = mysqli_num_rows($categoriesResult);

                        $uploadDate = strtotime($image['uploadDate']);
                        $formatDate = date("m/d/y g:i A", $uploadDate);

                        echo "
                        <div class='otherPost'> 
                            <a class='viewOtherPost' href='viewpost.php?post=".$image['imageId']."'>
                            <img src='../posts/".$image['imageName']."'/> 
                            <div class='otherUploads'>
                                <div class='otherUploadsProfile'>
                                    <img src='../profiles/".$image['profilePicture']."'>
                                    <div class='uploadDetails'>
                                        <p> ".$image['profileName']." </p>
                                        <p> ".$formatDate." </p>
                                    </div>
                                </div> 
                                <div class='otherPostCategories'>";
                                    if($categoryCollection>0){
                                        while ($category = mysqli_fetch_assoc($categoriesResult)){
                                            echo " <p> ".$category['category']." </p> ";
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
        </div>
    </main>
</body>
</html>