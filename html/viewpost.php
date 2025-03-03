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

    $sessionExpiration = 1800;
    if ($loggedIn==true){
        if (isset($_SESSION['latestActivity']) && (time() - $_SESSION['latestActivity']) > $sessionExpiration) {
            header("Location: logout.php");
            exit();
        }
        $_SESSION['latestActivity'] = time();
    }

    if(isset($_GET['post'])){
        $existImage = "SELECT * FROM images WHERE imageId='$_GET[post]' AND deleteStatus!=1";
        $existImageResult = mysqli_query($conn, $existImage);
        $isImageExist = mysqli_num_rows($existImageResult);
        if ($isImageExist>0){

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
    if(isset($_GET['deletePostId'])){
        $query = "UPDATE images SET deleteStatus=1 WHERE imageId='$_GET[deletePostId]'";
        $delete = mysqli_query ($conn, $query);
        $notifQuery = "INSERT INTO notifications (notifierId, notifType, notifiedId, notifyDate, readStatus)
        VALUES ('".$_SESSION['userId']."', 13, '".$_SESSION['userId']."', now(), 1)";
        $notif = mysqli_query ($conn, $notifQuery);
        header("location:home.php");
        die();
    }
    if(isset($_GET['deleteCommentId'])){
        $query = "DELETE FROM comments WHERE commentId='$_GET[deleteCommentId]'";
        $deleteResult = mysqli_query ($conn, $query);
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
                            $formatDate = date("F j, Y g:i A", $uploadDate);
                            echo "<p class='date'> $formatDate </p>"; 

                            $getCategories = "SELECT * FROM categories WHERE imageId='$_GET[post]'";
                            $categoriesResult = mysqli_query($conn, $getCategories);
                            $categoryCollection = mysqli_num_rows($categoriesResult);

                            echo "<div class='postCategories'>";
                                    if($categoryCollection>0){
                                        while ($category = mysqli_fetch_assoc($categoriesResult)){
                                            if($category['category']!='None'){
                                                echo " <a class='cat' href='home.php?category=".$category['category']."'> <p> ".$category['category']." </p> </a>";
                                            }
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
                else if ($_SESSION['userType']==1) {
                    echo "
                        <div class='actions ownerActions'>
                            <a class='deleteAction' href='javascript:void()' onClick='delAlert2(".$_GET['post'].")'>
                                <img src='../images/delete.png'/>
                                <p class='followed'> Delete </p>
                            </a>
                        </div>
                    ";

                }
                else if ($uploader['profileId'] != $_SESSION['userId']){
                    $getLikeStatus = "SELECT * FROM likes WHERE profileId='".$_SESSION['userId']."' AND imageId='$_GET[post]'";
                    $likeStatusResult = mysqli_query($conn, $getLikeStatus);
                    $likeStatus = mysqli_num_rows($likeStatusResult);

                    $getFollowStatus = "SELECT * FROM follow WHERE followerId='".$_SESSION['userId']."' AND followedId='".$uploader['profileId']."' AND followStatus=1";
                    $followStatusResult = mysqli_query($conn, $getFollowStatus);
                    $followStatus = mysqli_num_rows($followStatusResult);

                    $uploaderName = $uploader['profileName'];
                    $uploaderFirstName = strtok($uploaderName, " ");

                    if ($likeStatus==1) {
                        echo "
                            <div class='actions'>
                            <a class='likeOn' href='removeLikeStatus.php?unlikePostId=".$_GET['post']."'>
                            <img src='../images/likeOn.png'/>
                            <p class='active'> Liked </p>
                            </a>";
                    }
                    else {
                        echo "
                            <div class='actions'>
                            <a class='likeOff' href='addLikeStatus.php?likePostId=".$_GET['post']."'>
                            <img src='../images/likeOff.png'/>
                            <p class='active'> Like Post </p>
                            </a>";
                    }
                        
                    if ($followStatus==1) {
                        echo"
                            <a class='followOn' href='removeFollowStatus.php?followId=".$uploader['profileId']."'>
                            <img src='../images/followOn.png'/>
                            <p> Following </p>
                            </a>";
                    }
                    else {
                        echo"
                        <a class='followOff' href='addFollowStatus.php?followId=".$uploader['profileId']."'>
                        <img src='../images/followOff.png'/>
                        <p> Follow $uploaderFirstName </p>
                        </a>";
                    }

                    $checkReport = "SELECT * FROM reports WHERE reporterId='".$_SESSION['userId']."' AND reportedId='".$uploader['userId']."' AND reportType=1 AND imageId='".$_GET['post']."'";
                    $checkReportResult = mysqli_query($conn, $checkReport);
                    $checkReportRow = mysqli_num_rows($checkReportResult);
                    $report = mysqli_fetch_assoc($checkReportResult);
                        
                    if ($checkReportRow!=0){
                        echo "
                            <a class='followOn' href='removeReport.php?reportId=".$report['reportId']."'>
                            <img src='../images/report.png'/>
                            <p> Reported </p>
                            </a>
                        </div>";
                    }
                    else {
                        echo "<a class='followOff' href='#divOne'>
                            <img src='../images/report.png'/>
                            <p> Report Post </p>
                            </a>
                        </div>";
                    }
                }
                else {
                    echo "
                        <div class='actions ownerActions'>
                                <a class='likesTotal' href='#'>
                                        <img src='../images/likes.png'/>
                                        <p class='active'> ".$totalLikes." Likes </p>
                                </a>
                                <a class='editAction' href='editpost.php?post=".$_GET['post']."'>
                                    <img src='../images/edit.png'/>
                                    <p class='followed'> Edit </p>
                                 </a>
                                 <a class='deleteAction' href='javascript:void()' onClick='delAlert2(".$_GET['post'].")'>
                                    <img src='../images/delete.png'/>
                                    <p class='followed'> Delete </p>
                                 </a>
                        </div>
                    ";
                }
            ?>
        </div>
        <div class="commentSection">
            <h1> Comments </h1>
            <?php
                if (isset($_SESSION['userType']) && $_SESSION['userType']==1){
                    echo "";
                }
                else if (isset($_SESSION['userId'])){
                    $getCommentor = "SELECT * FROM users WHERE profileId=".$_SESSION['userId']."";
                    $commentorResult = mysqli_query($conn, $getCommentor);
                    $commentor = mysqli_fetch_assoc($commentorResult);
                    echo "
                        <div class='commentor'>
                            <form method='POST'>
                                <a class='ownerComment' href='profile.php'><img src='../profiles/".$commentor['profilePicture']."' alt=''></a>
                                <input type='text' id='comment' name='comment' placeholder='Type something...' required>
                                <input type='submit' name='submit' value='Comment'>
                            </form>
                        </div>
                    ";
                    if(isset($_POST["submit"])){
                        $newComment = $_POST["comment"];

                        $addNewComment = "INSERT INTO comments (comment, commentorId, postId, dateCommented) VALUES ('$newComment', ".$_SESSION['userId'].", '$_GET[post]', now())";
                        $addComment = mysqli_query($conn, $addNewComment);

                        $postComment = "SELECT userId FROM images WHERE imageId='".$_GET['post']."'";
                        $postCommentResult = mysqli_query ($conn, $postComment);
                        $posterAccount = mysqli_fetch_assoc($postCommentResult);
                        $notified = $posterAccount['userId'];

                        $notifQuery = "INSERT INTO notifications (notifierId, notifType, imageId, notifiedId, notifyDate, readStatus)
                            VALUES ('".$_SESSION['userId']."', 2, '".$_GET['post']."', $notified, now(), 0)";
                            $notif = mysqli_query ($conn, $notifQuery);
                    }
                }
                else {
                    echo '';
                }
            ?>
            
            
            <div class="comments">
                <?php
                    $getComments = "SELECT * FROM comments INNER JOIN users 
                    ON comments.commentorId=users.profileId WHERE postId='$_GET[post]' ORDER BY dateCommented DESC";
                    $commentsResult = mysqli_query($conn, $getComments);
                    $commentsRow = mysqli_num_rows($commentsResult);
                    

                    if($commentsRow>0) {
                        while ($comment = mysqli_fetch_assoc($commentsResult)){
                            $commentDate = strtotime($comment['dateCommented']);
                            $commentFormatDate = date("M j, Y", $commentDate)."<br>".date("g:i A", $commentDate);
                            echo "
                                <div class='commentorSection'> ";

                                if ($loggedIn===false){
                                    echo "
                                    <a class='otherComment' href='viewprofile.php?profile=".$comment['profileId']."'><img src='../profiles/".$comment['profilePicture']."' alt=''></a>
                                    <div class='comment'>
                                    <b><a class='commentorName' href='viewprofile.php?profile=".$comment['profileId']."'> ".$comment['profileName']." </a></b>";
                                }
                                else if ($comment['profileId']!=$_SESSION['userId']){
                                    echo "
                                    <a class='otherComment' href='viewprofile.php?profile=".$comment['profileId']."'><img src='../profiles/".$comment['profilePicture']."' alt=''></a>
                                    <div class='comment'>
                                    <b><a class='commentorName' href='viewprofile.php?profile=".$comment['profileId']."'> ".$comment['profileName']." </a></b>";
                                }
                                else {
                                    echo "
                                    <a class='otherComment' href='profile.php?profile=".$comment['profileId']."'><img src='../profiles/".$comment['profilePicture']."' alt=''></a>
                                    <div class='comment'>
                                    <b><a class='commentorName' href='profile.php?profile=".$comment['profileId']."'> ".$comment['profileName']." </a></b>";
                                }
                                echo "
                                        <p class='comment'> ".$comment['comment']." </p>
                                    </div>  
                                    <div class='commentDetails'>
                                        <p class='commentDate'> $commentFormatDate </p>
                                    </div>";
                                    if (!isset($_SESSION['userId'])){
                                        echo "";
                                    }
                                    else if($comment['profileId']==$_SESSION['userId'] || $uploader['profileId']==$_SESSION['userId'] || $_SESSION['userType']==1){
                                        echo "<a class='deleteComment' href='javascript:void()' onClick='delAlert(".$comment['commentId'].", ".$_GET['post'].")'><img src='../images/deleteComment.png'/></a>";
                                    }
                                    else {
                                        echo "";
                                    }
                                echo "</div>
                            ";

                        }
                    }
                    else {
                        echo "<p style='color: white; text-align: center;'> No Comments Yet. </p>";
                    }
                ?>
            </div>
        </div>
        <div class="otherPosts">
            <?php
                $getImages = "SELECT * FROM images 
                INNER JOIN users 
                ON images.userId=users.profileId
                WHERE userId='".$uploader['profileId']."'
                AND imageId!='$_GET[post]' 
                AND deleteStatus!=1 
                ORDER BY uploadDate DESC";
                $imageResult = mysqli_query($conn, $getImages);
                $images = mysqli_num_rows($imageResult);

                if($images>0){
                    echo "
                        <h1> ".$uploader['profileName']."'s Other Uploads</h1>
                        <div class='container'>
                    ";
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
        </div>
        <div class="overlay" id="divOne">
            <div class="wrapper">
                <h2>Report Details</h2><a class="close" href="#">&times;</a>
                <div class="content">
                    <div class="form-container">
                        <form method="POST" enctype="multipart/form-data">
                            <label>Reason</label> 
                            <textarea name="reason" maxlength='30' required placeholder="Reason for reporting"></textarea>
                            <input type="submit" name="report" value="Report">
                            <?php
                                if(isset($_POST["report"])){
                                    $reason = htmlspecialchars(trim($_POST['reason']));
                                    if(!empty($reason)){
                                        $recordReport = "INSERT INTO reports (reportedId, reportReason, imageId, reportType, reporterId, reportDate) 
                                        VALUES ('".$uploader['userId']."', '$reason', '".$_GET['post']."', 1, '".$_SESSION['userId']."', now())";
                                        $reportQuery = mysqli_query($conn, $recordReport);
                                        echo "<p class='success'> Post reported successfully. </p>";
                                    }
                                }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        function delAlert(id, postId){
            sts = confirm ("Are you sure you want to delete this comment?");
            if (sts){
                document.location.href=`viewpost.php?deleteCommentId=${id}&&post=${postId}`;
            }
        }
        function delAlert2(postId){
            sts = confirm ("Are you sure you want to delete this post?");
            if (sts){
                document.location.href=`viewpost.php?deletePostId=${postId}&&post=${postId}`;
            }
        }
    </script>
</body>
</html>