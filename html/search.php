<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="../css/search.css">
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $search = htmlspecialchars($_POST['search']);
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
                            <a href="home.php"> Home </a>
                            <a class="button" href="#divOne"> Logout </a>
                        ';
                    }
                    else if ($loggedIn == true && $_SESSION['userType']==1){
                        echo '  
                            <a href="reports.php"> Reports </a>
                            <a href="userManage.php"> Accounts </a>
                            <a href="home.php"> Home </a>
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
        <div class='container'>
        <?php
            if (!empty($search)){
                $searchUser = "SELECT * FROM users WHERE (profileName LIKE '%$search%' OR profileEmail LIKE '%$search%') AND profileType!=1";
                $searchResult = mysqli_query($conn, $searchUser);
                $searchRows = mysqli_num_rows($searchResult);
                if ($searchRows>0){
                    echo "<h1 class='title'> People </h1>";
                    while ($people = mysqli_fetch_assoc($searchResult)){
                        if ($people["profileId"]!=$_SESSION["userId"]){
                            echo "
                            <a class='people-container' href='viewprofile.php?profile=".$people['profileId']."'>
                                <img src='../profiles/".$people['profilePicture']."'/> 
                                <div class='people-details'>
                                    <h2> ".$people['profileName']." </h2>
                                    <p> ".$people['profileAddress']." </p>
                                </div>
                            </a>
                        ";
                        }
                        else {
                            echo "
                            <a class='people-container' href='profile.php'>
                                <img src='../profiles/".$people['profilePicture']."'/> 
                                <div class='people-details'>
                                    <h2> ".$people['profileName']." </h2>
                                    <p> ".$people['profileAddress']." </p>
                                </div>
                            </a>
                        ";
                        }
                    }
                }
                $searchPost = "SELECT * FROM images JOIN users ON images.userId=users.profileId WHERE images.imageDescription LIKE '%$search%' OR users.profileName LIKE '%$search%' AND deleteStatus!=1";
                $postResult = mysqli_query($conn, $searchPost);
                $postRows = mysqli_num_rows($postResult);
                if ($postRows>0){
                    echo "<h1 class='title'> Posts </h1>";
                    while ($post = mysqli_fetch_assoc($postResult)){
                        echo "
                        <a class='people-container' href='viewpost.php?post=".$post['imageId']."'>
                            <img src='../posts/".$post['imageName']."'/> 
                            <div class='people-details'>
                                <h2> ".$post['imageDescription']." </h2>
                                <p> Posted by ".$post['profileName']." </p>
                            </div>
                        </a>
                    ";
                    }
                }
                
                if ($searchRows===0 && $postRows===0) {
                    echo "<h1 class='empty'> No results found. </h1>";
                }
            }
            else {
                echo "<p class='empty'> Search something. </p>";
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