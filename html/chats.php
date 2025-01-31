<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chats</title>
    <link rel="stylesheet" href="../css/chats.css">
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

        if($_SESSION['userType']!=2 || !isset($_SESSION['userId'])){
            header("location: home.php");
        }

        $userId=$_SESSION['userId'];

        $getFollowing = "SELECT * FROM follow WHERE followerId=$userId ORDER BY lastMessageDate DESC";
        $followingResult = mysqli_query($conn, $getFollowing);
        if(isset($_GET['deleteChatId'])){
            $query = "DELETE FROM chats WHERE chatId='$_GET[deleteChatId]'";
            $deleteResult = mysqli_query ($conn, $query);
            header("Location: chats.php?message=".$_GET['message']);
            die();
        }
        if(isset($_GET['message'])){
            $existChat = "SELECT * FROM users WHERE profileId='$_GET[message]'";
            $existChatResult = mysqli_query($conn, $existChat);
            $isChatExist = mysqli_num_rows($existChatResult);
            if ($isChatExist>0){
                $readChat = "UPDATE chats SET readStatus=1 WHERE messengerId='".$_GET['message']."' AND receiverId=$userId";
                $chatResult = mysqli_query($conn, $readChat);
            }
            else {
                header("location:chats.php");
                die();
            }
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
                    <a href="uploadimage.php"> Upload </a>
                    <a href="notifications.php"> Notifications </a>
                    <a class="active" href="chats.php"> Chats </a>
                    <a href="home.php"> Home </a>
                    <a class="button" href="#divOne"> Logout </a>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <div class="people scrollable">
            <h1>Chats</h1>
            <?php
                $followingRow = mysqli_num_rows($followingResult);
                if ($followingRow>0){
                    while ($followedUser = mysqli_fetch_assoc($followingResult)){
                        $getFollowingUser = "SELECT * FROM users WHERE profileId='".$followedUser['followedId']."'";
                        $followingUserResult = mysqli_query($conn, $getFollowingUser);
                        $followingUser = mysqli_fetch_assoc($followingUserResult);

                        $getChatDetails = "SELECT * FROM chats WHERE (receiverId='$userId' OR receiverId='".$followedUser['followedId']."') 
                        AND (messengerId='".$followedUser['followedId']."' OR messengerId='$userId') ORDER BY messageDate DESC";
                        $chatDetailsResult = mysqli_query($conn, $getChatDetails);
                        $chatsRow = mysqli_num_rows($chatDetailsResult);
                        $chatDetails = mysqli_fetch_assoc($chatDetailsResult);

                        if ($chatsRow>0){
                            $chatDate = strtotime($chatDetails['messageDate']);
                            $chatFormatDate = date("M j, Y", $chatDate)."<br>".date("g:i A", $chatDate);
                            $message = $chatDetails['message'];
                            $checkMessage = (strlen($message) > 20) ? substr($message, 0, 20) . "..." : $message;
                            echo "
                            <a class='chathead' href='chats.php?message=".$followedUser['followedId']."'><div class='person'>
                                <img class='followingProfile' src='../profiles/".$followingUser['profilePicture']."'/>
                                <div class='details'>
                                    <h2> ".$followingUser['profileName']."</h2>";
                                    if ($chatDetails['messengerId']==$_SESSION['userId']){
                                        echo "<p class='message'> You: ".htmlspecialchars($checkMessage)." </p>";
                                    }
                                    else {
                                        if ($chatDetails['readStatus']!=1){
                                            echo "<b><p class='message' style='color: white;'> ".htmlspecialchars($checkMessage)." </p></b>";
                                        }
                                        else {
                                            echo "<p class='message'> ".htmlspecialchars($checkMessage)." </p>";
                                        }
                                    }
                                echo "</div>
                                <p class='date'> $chatFormatDate</p>
                            </div></a>
                            ";
                        }
                        else {
                            echo "
                            <a class='chathead' href='chats.php?message=".$followedUser['followedId']."'><div class='person'>
                                <img class='followingProfile' src='../profiles/".$followingUser['profilePicture']."'/>
                                <div class='details'>
                                    <h2> ".$followingUser['profileName']."</h2>
                                    <div class='chatDetails'>
                                        <p class='nothing'> No Messages Yet. </p>
                                    </div>
                                </div>
                            </div></a>
                            ";
                        }
                    }
                }
                else {
                    echo"<h3 class='none'> Follow Someone to <br> message them. </h3>";
                }
            ?>
        </div>
        <div class="chatContents">
            <?php
                if(!empty($_GET['message'])){
                    $getFollowedUser ="SELECT * FROM users WHERE profileId='".$_GET['message']."'";
                    $followedUserResult = mysqli_query($conn, $getFollowedUser);
                    $followedUserDetails = mysqli_fetch_assoc($followedUserResult);
                    echo "
                    <div class='selectedChatHeader'>
                        <img src='../profiles/".$followedUserDetails['profilePicture']."'/>
                        <h1> ".$followedUserDetails['profileName']." </h1>
                    </div>
                    ";
                }
                else {
                    echo "<div class='error'><h1> Select a Chat </h1> </div>";
                }
            ?>
            <div class="sendMessage">
                <?php
                    if(!empty($_GET['message'])){
                        $getMessages = "SELECT * FROM chats WHERE (receiverId=$userId OR receiverId=".$_GET['message'].") AND (messengerId=$userId OR messengerId=".$_GET['message'].")";
                        $messagesResult = mysqli_query($conn, $getMessages);
                        $messagesRow = mysqli_num_rows($messagesResult);

                        if ($messagesRow>0){
                            while ($messagesDetails = mysqli_fetch_assoc($messagesResult)){
                                if ($messagesDetails['messengerId']==$userId) {
                                    echo "
                                        <div class='rightSide'>
                                            <a class='deleteComment' href='javascript:void()' onClick='delAlert(".$messagesDetails['chatId'].", ".$_GET['message'].")'>...</a>
                                            <p class='imageMessage'> ".$messagesDetails['message']." ";
                                            if ($messagesDetails['imageName']!=NULL){
                                                echo "<img class='messageImage' src='../chats/".$messagesDetails['imageName']."'/> </p>";
                                            }
                                            else {
                                                echo "";
                                            }
                                        echo"</div>
                                    ";
                                }
                                else {
                                    echo "
                                        <div class='leftSide'>
                                            <img class='messenger' src='../profiles/".$followedUserDetails['profilePicture']."'/>
                                            <p class='imageMessage'> ".$messagesDetails['message']." ";
                                            if ($messagesDetails['imageName']!=NULL){
                                                echo "<img class='messageImage' src='../chats/".$messagesDetails['imageName']."'/> </p>";
                                            }
                                            else {
                                                echo "";
                                            }
                                        echo "</div>
                                    ";
                                }
                            }
                        }
                        else {
                            echo "<div class='none'> <p> No Messages Yet. </p> </div>";
                        }
                    }
                    else {
                        echo"";
                    }
                ?>
                <form method="POST" enctype="multipart/form-data">
                    <?php 
                    if(!empty($_GET['message'])){
                        $getUser = "SELECT * FROM users WHERE profileId=$userId";
                        $userResult = mysqli_query($conn, $getUser);
                        $userDetails = mysqli_fetch_assoc($userResult);
                        echo "
                            <div id='imageSection' class='column'>
                                <img id='uploadedimage' alt='Selected Image'>
                                <p class='preview'> Preview </p>
                            </div>
                            <div class='messageSection'>
                                <img class='ownerProfile' src='../profiles/".$userDetails['profilePicture']."'/>
                                <input type='text' id='message' name='message' maxlength='50' placeholder='Type something...' required>
                                <input type='file' id='uploadimage' name='image' accept='image/*'>
                                <input type='submit' name='submit' value='Send'>
                            </div>
                        ";
                        if(isset($_POST["submit"])){
                            $newMessage = htmlspecialchars(trim($_POST['message']));

                            $imageFile = $_FILES["image"]["name"];
                            $date = date('mdY');
                            $fileExtension = pathinfo($imageFile, PATHINFO_EXTENSION);
                            $newImageFile = pathinfo($imageFile, PATHINFO_FILENAME) . "_" . $date . "_" .time(). "." . $fileExtension;
                            $imageTempName = $_FILES["image"]["tmp_name"];
                            $folder = "../chats/";

                            if(!empty($imageFile)){
                                if ($fileExtension=='jpg' || $fileExtension=="JPG" || $fileExtension=="png" || $fileExtension=="PNG"){
                                    $addMessage = "INSERT INTO chats (message, imageName, messengerId, receiverId, messageDate) 
                                    VALUES ('$newMessage', '$newImageFile', '$userId', '".$_GET['message']."', now())";
                                    $addNewMessage = mysqli_query($conn, $addMessage);
                                    $imageFilePath = $folder . $newImageFile;
                                    if (move_uploaded_file($imageTempName, $imageFilePath)) {
                                        header("Location: chats.php?message=". urlencode($_GET['message']));
                                        exit();
                                    } else {
                                        echo "<p class='error'> Failed to Upload </p>";
                                    }
                                }
                                else {
                                    echo "<p class='error'> Only JPG/PNG images allowed. </p>";
                               }
                            }
                            else {
                                $addMessage = "INSERT INTO chats (message, imageName, messengerId, receiverId, messageDate) 
                                VALUES ('$newMessage', NULL, '$userId', '".$_GET['message']."', now())";
                                $addNewMessage = mysqli_query($conn, $addMessage);
                            }
                            
                            $notifQuery = "INSERT INTO notifications (notifierId, notifType, notifiedId, notifyDate, readStatus)
                            VALUES ($userId, 4, '".$_GET['message']."', now(), 0)";
                            $notif = mysqli_query ($conn, $notifQuery);

                            $messageQuery = "UPDATE follow SET lastMessageDate=now() WHERE (followerId=$userId OR followedId=$userId) AND (followerId='".$_GET['message']."' OR followedId='".$_GET['message']."')";
                            $messageOutput = mysqli_query ($conn, $messageQuery);
                            }
                    }
                    else{
                        echo "";
                    }
                    ?>
                </form>
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
        const uploadimage = document.getElementById('uploadimage');
        const uploadedimage = document.getElementById('uploadedimage');
        const imageSection = document.getElementById('imageSection');
        uploadimage.onchange = evt => {
            const [file] = uploadimage.files;
            if (file) {
                uploadedimage.src = URL.createObjectURL(file);
                uploadedimage.style.display = 'block';
                imageSection.style.display = 'flex';
            }
        };
        function delAlert(id, messageId){
            sts = confirm ("Are you sure you want to delete this message?");
            if (sts){
                document.location.href=`chats.php?deleteChatId=${id}&&message=${messageId}`;
            }
        }
    </script>
</body>
</html>