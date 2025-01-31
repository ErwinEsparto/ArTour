<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management</title>
    <link rel="stylesheet" href="../css/addAdmin.css">
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
        
        if($_SESSION['userType']!=1){
            header("location: home.php");
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
                            <a class="active" href="#"> Accounts </a>
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
        <section class="form">
            <form method="POST">

                <div class="title">
                    <h1> Add Admin </h1>
                </div>
                
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Full Name" required>

                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Email Address" required>

                <label for="password">Password</label>
                <input type="password" id="password" minlength="8" name="password" placeholder="Password" required>
                
                <input type="submit" name="submit" value="Add">

                <?php 
                    if(isset($_POST["submit"])){
                        $name = $_POST["name"];
                        $email = $_POST["email"];
                        $password = $_POST["password"];
                        $encryptPassword = password_hash($password, PASSWORD_BCRYPT);

                        $isExist = "SELECT * FROM users WHERE profileEmail='$email'";
                        $checkExist = mysqli_query($conn, $isExist);
                        $row = mysqli_num_rows($checkExist);

                        if ($row == 0) {
                            $registerUser = "INSERT INTO users (profileType, profileName, profilePassword, profileEmail, profilePicture, dateCreated, activeStatus) 
                            VALUES (1, '$name', '$encryptPassword', '$email', 'default.jpg', curdate(), 1)";
                            $addUser = mysqli_query($conn, $registerUser);
                            echo "<p class='success'> Successfully Added. </p>";
                            header("refresh: 1.5; url = userManage.php");
                        }
                        else {
                            echo "<p class='error'> Email address already taken. </p>";
                        } 
                    }
                ?>
                <a class="return" href="userManage.php"> Go back to Accounts </a>
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