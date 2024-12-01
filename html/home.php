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

        $getImages = "SELECT * FROM images 
                INNER JOIN users 
                ON images.userId=users.profileId
                ORDER BY uploadDate DESC";
                $result = mysqli_query($conn, $getImages);

        if(isset($_GET['category'])){
            $category = $_GET['category'];

            if ($category=='All'){
                $selectedCategory = $category;
                $getImages = "SELECT * FROM images 
                INNER JOIN users 
                ON images.userId=users.profileId
                ORDER BY uploadDate DESC";
                $result = mysqli_query($conn, $getImages);
            }
            else {
                $selectedCategory = $category;
                $getImages = "SELECT * FROM images 
                JOIN users 
                ON images.userId=users.profileId
                JOIN categories 
                ON images.imageId=categories.imageId
                WHERE categories.category='$category'
                ORDER BY uploadDate DESC";
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
                <img src="../images/searchicon.png" alt="">
                <input type="search" id="search" name="search" placeholder="Search">
            </div>
        
            <div class="navigation">
                <nav class="sections">
                <?php
                    if ($loggedIn == true){
                        echo '  
                            <a href="uploadimage.php"> Upload an Image </a>
                            <a href="profile.php"> Profile </a>
                            <a class="button" href="logout.php"> Logout </a>
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
    </main>
</body>
</html>