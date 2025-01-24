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
        $userId=$_SESSION['userId'];

        $getFollowing = "SELECT * FROM follow WHERE followerId=$userId";
        $followingResult = mysqli_query($conn, $getFollowing);
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
                    <a class="button" href="logout.php"> Logout </a>
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
                            $checkMessage = (strlen($message) > 30) ? substr($message, 0, 30) . "..." : $message;
                            echo "
                            <a class='chathead' href='chats.php?message=".$followedUser['followedId']."'><div class='person'>
                                <img class='followingProfile' src='../profiles/".$followingUser['profilePicture']."'/>
                                <div class='details'>
                                    <h2> ".$followingUser['profileName']."</h2>
                                    <p class='message'> ".htmlspecialchars($checkMessage)." </p>
                                </div>
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
                    $getMessages = "SELECT * FROM chats WHERE (receiverId=$userId OR receiverId=".$_GET['message'].") AND (messengerId=$userId OR messengerId=".$_GET['message'].")";
                    $messagesResult = mysqli_query($conn, $getMessages);
                    $messagesRow = mysqli_num_rows($messagesResult);

                    if ($messagesRow>0){
                        while ($messagesDetails = mysqli_fetch_assoc($messagesResult)){
                            if ($messagesDetails['messengerId']==$userId) {
                                echo "
                                    <div class='rightSide'>
                                        <p> ".$messagesDetails['message']." </p>
                                    </div>
                                ";
                            }
                            else {
                                echo "
                                    <div class='leftSide'>
                                        <img class='messenger' src='../profiles/".$followedUserDetails['profilePicture']."'/>
                                        <p> ".$messagesDetails['message']." </p>
                                    </div>
                                ";
                            }
                        }
                    }
                    else {
                        echo "<div class='none'> <p> No Messages Yet. </p> </div>";
                    }
                ?>
                <form method="POST">
                    <?php 
                        $getUser = "SELECT * FROM users WHERE profileId=$userId";
                        $userResult = mysqli_query($conn, $getUser);
                        $userDetails = mysqli_fetch_assoc($userResult);
                        echo "<img class='ownerProfile' src='../profiles/".$userDetails['profilePicture']."'/>";
                    ?>
                    <input type="text" id="message" name="message" placeholder="Type something..." required>
                    <input type="submit" name="submit" value="Send">
                    <?php 
                        if(isset($_POST["submit"])){
                            $newMessage = $_POST["message"];
                            $addMessage = "INSERT INTO chats (message, messengerId, receiverId, messageDate) 
                            VALUES ('$newMessage', '$userId', '".$_GET['message']."', now())";
                            $addNewMessage = mysqli_query($conn, $addMessage);
                            header("Location: chats.php?message=". urlencode($_GET['message']));
                            exit();
                        }
                    ?>
                </form>
            </div>
        </div>
    </main>
</body>
</html>