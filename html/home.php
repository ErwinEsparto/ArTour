<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArTour</title>
    <link rel="stylesheet" href="../css/home.css">
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
        $selectedCategory = (empty($_GET['category'])) ? 'All' : '';

        $_SESSION['imagesLimit'] = 8;
        if (isset($_GET['more'])){
            $_SESSION['imagesLimit'] += $_GET['more'];
        }
        else {
            $_SESSION['imagesLimit'] = 8;
        }

        $getImages = "SELECT * FROM images 
                INNER JOIN users 
                ON images.userId=users.profileId 
                WHERE deleteStatus!=1
                ORDER BY uploadDate DESC LIMIT ".$_SESSION['imagesLimit']."";
                $result = mysqli_query($conn, $getImages);

        $sessionExpiration = 1800;
        if ($loggedIn==true){
            if (isset($_SESSION['latestActivity']) && (time() - $_SESSION['latestActivity']) > $sessionExpiration) {
                header("Location: logout.php");
                exit();
            }
            $_SESSION['latestActivity'] = time();
        }

        if(isset($_GET['category'])){
            $category = $_GET['category'];

            if ($category=='All'){
                $selectedCategory = $category;
                $getImages = "SELECT * FROM images 
                INNER JOIN users 
                ON images.userId=users.profileId 
                WHERE deleteStatus!=1
                ORDER BY uploadDate DESC LIMIT ".$_SESSION['imagesLimit']."";
                $result = mysqli_query($conn, $getImages);
            }
            else {
                $selectedCategory = $category;
                $getImages = "SELECT * FROM images 
                JOIN users 
                ON images.userId=users.profileId
                JOIN categories 
                ON images.imageId=categories.imageId
                WHERE categories.category='$category' AND deleteStatus!=1
                ORDER BY uploadDate DESC LIMIT ".$_SESSION['imagesLimit']."";
                $result = mysqli_query($conn, $getImages);
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
        <div class="categories">
            <p> Categories </p>
            <p> | </p>
            <a class="<?php echo ($selectedCategory == 'All') ? 'active' : ''; ?>" href="home.php"> All </a>
            <a class="<?php echo ($selectedCategory == 'Abstract') ? 'active' : ''; ?>" href="home.php?category=Abstract"> Abstract </a>
            <a class="<?php echo ($selectedCategory == 'Animal') ? 'active' : ''; ?>" href="home.php?category=Animal"> Animal </a>
            <a class="<?php echo ($selectedCategory == 'Architecture') ? 'active' : ''; ?>" href="home.php?category=Architecture"> Architecture </a>
            <a class="<?php echo ($selectedCategory == 'Digital') ? 'active' : ''; ?>" href="home.php?category=Digital"> Digital </a>
            <a class="<?php echo ($selectedCategory == 'Drawing') ? 'active' : ''; ?>" href="home.php?category=Drawing"> Drawing </a>
            <a class="<?php echo ($selectedCategory == 'Game') ? 'active' : ''; ?>" href="home.php?category=Game"> Game </a>
            <a class="<?php echo ($selectedCategory == 'Nature') ? 'active' : ''; ?>" href="home.php?category=Nature"> Nature </a>
            <a class="<?php echo ($selectedCategory == 'Painting') ? 'active' : ''; ?>" href="home.php?category=Painting"> Painting </a>
            <a class="<?php echo ($selectedCategory == 'Photography') ? 'active' : ''; ?>" href="home.php?category=Photography"> Photography </a>
            <a class="<?php echo ($selectedCategory == 'Sculpture') ? 'active' : ''; ?>" href="home.php?category=Sculpture"> Sculpture </a>
        </div>
    </header>
    <main>
        <div class="container">
            <?php
                $imageCollection = mysqli_num_rows($result);

                if($imageCollection>0){
                    while ($image = mysqli_fetch_assoc($result)){
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
                                <div class='profile'>
                                    <img src='../profiles/".$image['profilePicture']."'>
                                    <div class='details'>
                                        <p> ".$image['profileName']." </p>
                                        <p> ".$formatDate." </p>
                                    </div>
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
        <?php
            $getImagesRow = "SELECT * FROM images WHERE deleteStatus!=1";
            $imagesRowResult = mysqli_query($conn, $getImagesRow);
            $maxImagesRow = mysqli_num_rows($imagesRowResult);
            $maxFilterImagesRow = mysqli_num_rows($result);

            if (isset($_GET['category'])){
                if(isset($_GET['more'])){
                    if ($maxFilterImagesRow>$_GET['more']){
                        $newLimit = $_GET['more'] + 8;
                        echo "<a class='more' href='home.php?category=".$_GET['category']."&&more=$newLimit'> Load More </a>";
                    }
                    else {
                        echo "";
                    }
                }
                else {
                    if ($maxFilterImagesRow>8){
                        echo "<a class='more' href='home.php?category=".$_GET['category']."&&more=8'> Load More </a>";
                    }
                    else {
                        echo "";
                    }
                }
            }
            else {
                if(isset($_GET['more'])){
                    if ($maxImagesRow>$_GET['more']){
                        $newLimit = $_GET['more'] + 8;
                        echo "<a class='more' href='home.php?more=$newLimit'> Load More </a>";
                    }
                    else {
                        echo "";
                    }
                }
                else {
                    if ($maxImagesRow>8){
                        echo "<a class='more' href='home.php?more=8'> Load More </a>";
                    }
                    else {
                        echo "";
                    }
                }
            }
        ?>
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