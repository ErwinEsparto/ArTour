<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="../css/editpost.css">
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

        $getPost = "SELECT * FROM images WHERE imageId='$_GET[post]'";
        $postResult = mysqli_query($conn, $getPost);
        $post = mysqli_fetch_assoc($postResult);

        $loggedIn = $_SESSION['loggedIn'] ?? false;

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
            if($_SESSION['userId']==$post['userId']){
                if ($isImageExist>0){
    
                }
                else {
                    header("location:home.php");
                    die();
                }
            }
            else {
                header("location:home.php");
                die();
            }
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
        <section class="form">
            <form method="POST" enctype="multipart/form-data">
                <div class="column">
                    <div class="profile">
                        <a class="profileLink" href="profile.php"><img src="../profiles/<?php echo $account['profilePicture']; ?>"  alt=""> </a>
                        <div class="details">
                            <input type="text" id="name" name="name" value="<?php echo $account['profileName']; ?>" readonly>
                            <input type="text" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" readonly>
                        </div>
                    </div>

                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Type something..."><?php echo $post['imageDescription']; ?></textarea>

                    <label for="category"> Update Image Category: </label>
                    <div class="selectCategories">
                        <select id="category1" name="category1">
                            <?php 
                                $categories = array("Abstract", "Animal", "Architecture", "Digital", "Drawing", "Game", "Nature", "Painting", "Photography", "Sculpture"); 

                                foreach ($categories as $cat) {
                                    $checkCat = "SELECT category FROM categories WHERE imageId='$_GET[post]'";
                                    $catResult = mysqli_query($conn, $checkCat);
                                    $catType = mysqli_fetch_assoc($catResult);
        
                                    if ($catType['category']==$cat){
                                        echo "<option value=$cat style='color:white;'> $cat </option>";
                                    }
                                }
                                foreach ($categories as $cat) {
                                    $checkCat = "SELECT category FROM categories WHERE imageId='$_GET[post]'";
                                    $catResult = mysqli_query($conn, $checkCat);
                                    $catType = mysqli_fetch_assoc($catResult);
        
                                    if ($catType['category']!=$cat){
                                        echo "<option value=$cat style='color:white;'> $cat </option>";
                                    }
                                }
                            ?>
                        </select>
                        <select id="category2" name="category2">
                            <?php 
                                $categories = array("None", "Abstract", "Animal", "Architecture", "Digital", "Drawing", "Game", "Nature", "Painting", "Photography", "Sculpture"); 

                                foreach ($categories as $cat) {
                                    $checkCat = "SELECT category FROM categories WHERE imageId='$_GET[post]' LIMIT 1 OFFSET 1";
                                    $catResult = mysqli_query($conn, $checkCat);
                                    $catType = mysqli_fetch_assoc($catResult);
        
                                    if ($catType['category']==$cat){
                                        if($cat!='None'){
                                            echo "<option value=$cat style='color:white;'> $cat </option>";
                                        }
                                        else if($cat=='None'){
                                            echo "<option value=$cat style='color:white;'> $cat </option>";
                                        }
                                    }
                                }
                                foreach ($categories as $cat) {
                                    $checkCat = "SELECT category FROM categories WHERE imageId='$_GET[post]' LIMIT 1 OFFSET 1";
                                    $catResult = mysqli_query($conn, $checkCat);
                                    $catType = mysqli_fetch_assoc($catResult);
        
                                    if ($catType['category']!=$cat){
                                        echo "<option value=$cat style='color:white;'> $cat </option>";
                                    }
                                }
                            ?>
                        </select>
                        <select id="category3" name="category3">
                            <?php 
                                $categories = array("None1", "Abstract", "Animal", "Architecture", "Digital", "Drawing", "Game", "Nature", "Painting", "Photography", "Sculpture"); 

                                foreach ($categories as $cat) {
                                    $checkCat = "SELECT category FROM categories WHERE imageId='$_GET[post]' LIMIT 1 OFFSET 2";
                                    $catResult = mysqli_query($conn, $checkCat);
                                    $catType = mysqli_fetch_assoc($catResult);
        
                                    if ($catType['category']==$cat){
                                        if($cat!='None1'){
                                            echo "<option value=$cat style='color:white;'> $cat (Current) </option>";
                                        }
                                        else if($cat=='None1'){
                                            echo "<option value=$cat style='color:white;'> None </option>";
                                        }
                                    }
                                }
                                foreach ($categories as $cat) {
                                    $checkCat = "SELECT category FROM categories WHERE imageId='$_GET[post]' LIMIT 1 OFFSET 2";
                                    $catResult = mysqli_query($conn, $checkCat);
                                    $catType = mysqli_fetch_assoc($catResult);
        
                                    if ($catType['category']!=$cat){
                                        if($cat!='None1'){
                                            echo "<option value=$cat style='color:white;'> $cat </option>";
                                        }
                                        else if($cat=='None1'){
                                            echo "<option value=$cat style='color:white;'> None </option>";
                                        }
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <input type="submit" name="submit" value="Save Changes">

                    <?php 
                        if(isset($_POST["submit"])){
                            $imageDescription = htmlspecialchars(trim($_POST['description']));
                            $imageCategory1 = $_POST["category1"];
                            $imageCategory2 = $_POST["category2"];
                            $imageCategory3 = $_POST["category3"];
                            $userId = $account["profileId"];
                            $imageId = $_GET["post"];

                            if ($imageCategory1!=$imageCategory2 && $imageCategory1!=$imageCategory3 && $imageCategory2 != $imageCategory3) {
                                $updateImg = "UPDATE images SET imageDescription='$imageDescription', uploadDate=now() WHERE imageId='$imageId'";
                                mysqli_query($conn, $updateImg);

                                $notifQuery = "INSERT INTO notifications (notifierId, notifType, imageId, notifiedId, notifyDate, readStatus)
                                    VALUES ('".$_SESSION['userId']."', 7, '".$_GET['post']."', '".$_SESSION['userId']."', now(), 1)";
                                $notif = mysqli_query ($conn, $notifQuery);

                                $addCategory1 = "UPDATE categories SET category='$imageCategory1' WHERE imageId='$imageId' LIMIT 1";
                                mysqli_query($conn, $addCategory1);

                                $addCategory2 = "UPDATE categories uc JOIN (SELECT categoryId, imageId, category FROM categories WHERE imageId='$imageId' ORDER BY categoryId LIMIT 1 OFFSET 1) 
                                as gc ON uc.imageId = gc.imageId SET uc.category='$imageCategory2' WHERE uc.categoryId = gc.categoryId;";
                                mysqli_query($conn, $addCategory2);

                                if ($imageCategory3!="None1"){
                                    $addCategory3 = "UPDATE categories uc JOIN (SELECT categoryId, imageId, category FROM categories WHERE imageId='$imageId' ORDER BY categoryId LIMIT 1 OFFSET 2) 
                                    as gc ON uc.imageId = gc.imageId SET uc.category='$imageCategory3' WHERE uc.categoryId = gc.categoryId;";
                                    mysqli_query($conn, $addCategory3);
                                }
                                else {
                                    $addCategory3 = "UPDATE categories uc JOIN (SELECT categoryId, imageId, category FROM categories WHERE imageId='$imageId' ORDER BY categoryId LIMIT 1 OFFSET 2) 
                                    as gc ON uc.imageId = gc.imageId SET uc.category='None' WHERE uc.categoryId = gc.categoryId;";
                                    mysqli_query($conn, $addCategory3);
                                }
                                echo "<p class='success'> Changes Saved. </p>";
                            }
                            else {
                                echo "<p class='error'> Please differentiate each category. </p>";
                            }
                        }
                    ?>

                    <a class="return" href="home.php"> Cancel </a>
                </div>
                <div id="imageSection" class="column">
                <img src="../posts/<?php echo $post['imageName']; ?>" id="uploadedimage">
                </div>
            </form>
        </section>
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