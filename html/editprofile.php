<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../css/editprofile.css">
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
                    <a href="uploadimage.php"> Upload an Image </a>
                    <a href="profile.php"> Profile </a>
                    <a class="button" href="logout.php"> Logout </a>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <section class="form">
            <form method="POST">

                <div class="column">
                    <h1> Personal Information </h1>

                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Full Name" value="<?php echo $account['profileName']; ?>" required>

                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" placeholder="Address" value="<?php echo $account['profileAddress']; ?>" required>

                    <h1> Contact Details </h1>

                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Email Address" value="<?php echo $account['profileEmail']; ?>" required>
                    
                    <label for="number">Contact Number</label>
                    <input type="text" id="number" name="number" placeholder="Contact Number" value="<?php echo $account['profileNumber']; ?>" required>
                </div>

                <div class="column">
                    <h1> Profile Description</h1>

                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Type something..." ><?php echo $account['profileDescription']; ?></textarea>

                    <h1> Socials </h1>
                    
                    <label for="facebook">Facebook</label>
                    <input type="text" id="facebook" name="facebook" placeholder="Facebook Account" value="<?php echo $account['profileFacebook']; ?>" required>

                    <label for="instagram">Instagram</label>
                    <input type="textarea" id="instagram" name="instagram" placeholder="Instagram Account" value="<?php echo $account['profileInstagram']; ?>"  required>

                    <label for="x">X</label>
                    <input type="textarea" id="x" name="x" placeholder="X Account" value="<?php echo $account['profileX']; ?>"  required>
                </div>

                <div class="saveColumn">
                    <div class="passwordColumn">
                        <h1> Change Password </h1>

                        <label for="current">Current Password</label>
                        <input type="password" id="current" name="current" placeholder="Current Password">

                        <label for="new">New Password</label>
                        <input type="password" id="new" name="new" minlength="8" placeholder="New Password">

                        <label for="confirm">Confirm Password</label>
                        <input type="password" id="confirm" name="confirm" minlength="8" placeholder="Confirm Password">
                    </div>
                    <div class="actions">
                        <input type="submit" name="submit" value="Save Changes">

                        <?php 
                            if(isset($_POST["submit"])){
                                $currentPassword = $_POST["current"];
                                $newPassword = $_POST["new"];
                                $confirmPassword = $_POST["confirm"];
                                $encryptPassword = password_hash($confirmPassword, PASSWORD_BCRYPT);

                                if (empty($currentPassword) && empty($newPassword) && empty($confirmPassword)) {
                                    $name = $_POST["name"];
                                    $address = $_POST["address"];
                                    $email = $_POST["email"];
                                    $number = $_POST["number"];
                                    $description = htmlspecialchars(trim($_POST['description']));
                                    $facebook = $_POST["facebook"];
                                    $instagram = $_POST["instagram"];
                                    $x = $_POST["x"];

                                    if ($account['profileEmail']==$email){
                                        $saveChanges = "UPDATE users SET profileName='$name', profileDescription='$description', profileAddress='$address', profileEmail='$email',
                                        profileNumber='$number', profileFacebook='$facebook', profileInstagram='$instagram', profileX='$x' WHERE profileId='".$_SESSION['userId']."'";
                                        $saveUser = mysqli_query($conn, $saveChanges);
                                        echo "<p class='success'> Saved Successfully. </p>";
                                        header("refresh: 1; url = profile.php");
                                    }
                                    else {
                                        $checkEmail = "SELECT * FROM users WHERE profileEmail='$email'";
                                        $emailResult = mysqli_query($conn, $checkEmail);
                                        $isExist = mysqli_num_rows($emailResult);

                                        if ($isExist==0){
                                            $saveChanges = "UPDATE users SET profileName='$name', profileDescription='$description', profileAddress='$address', profileEmail='$email',
                                            profileNumber='$number', profileFacebook='$facebook', profileInstagram='$instagram', profileX='$x' WHERE profileId='".$_SESSION['userId']."'";
                                            $saveUser = mysqli_query($conn, $saveChanges);
                                            echo "<p class='success'> Saved Successfully. </p>";
                                            header("refresh: 1; url = profile.php");
                                        }
                                        else {
                                            echo "<p class='error'> Email is already taken. </p>";
                                        }
                                    }
                                }
                                else{
                                    $checkUser = "SELECT * FROM users WHERE profileId='".$_SESSION['userId']."'";
                                    $userResult = mysqli_query($conn, $checkUser);

                                    if (empty($newPassword) || empty($confirmPassword)) {
                                        echo "<p class='error'> New passwords must not be empty. </p>";
                                    }
                                    else if ($newPassword != $confirmPassword) {
                                        echo "<p class='error'> New passwords are not the same. </p>";
                                    }
                                    else if (mysqli_num_rows($userResult) == 1) {
                                        $foundUser=mysqli_fetch_assoc($userResult);
                                        $userOldPassword = $foundUser['profilePassword'];

                                        if (!password_verify($confirmPassword, $userOldPassword)) {
                                            if(password_verify($currentPassword, $userOldPassword)){
                                                $name = $_POST["name"];
                                                $address = $_POST["address"];
                                                $email = $_POST["email"];
                                                $number = $_POST["number"];
                                                $description = htmlspecialchars(trim($_POST['description']));
                                                $facebook = $_POST["facebook"];
                                                $instagram = $_POST["instagram"];
                                                $x = $_POST["x"];

                                                if ($account['profileEmail']==$email){
                                                    $saveChanges = "UPDATE users SET profileName='$name', profilePassword='$encryptPassword', profileDescription='$description', profileAddress='$address', profileEmail='$email',
                                                    profileNumber='$number', profileFacebook='$facebook', profileInstagram='$instagram', profileX='$x' WHERE profileId='".$_SESSION['userId']."'";
                                                    $saveUser = mysqli_query($conn, $saveChanges);
                                                    echo "<p class='success'> Saved Successfully. </p>";
                                                    header("refresh: 1; url = profile.php");
                                                }
                                                else {
                                                    $checkEmail = "SELECT * FROM users WHERE profileEmail='$email'";
                                                    $emailResult = mysqli_query($conn, $checkEmail);
                                                    $isExist = mysqli_num_rows($emailResult);
            
                                                    if ($isExist==0){
                                                        $saveChanges = "UPDATE users SET profileName='$name', profilePassword='$encryptPassword', profileDescription='$description', profileAddress='$address', profileEmail='$email',
                                                        profileNumber='$number', profileFacebook='$facebook', profileInstagram='$instagram', profileX='$x' WHERE profileId='".$_SESSION['userId']."'";
                                                        $saveUser = mysqli_query($conn, $saveChanges);
                                                        echo "<p class='success'> Saved Successfully. </p>";
                                                        header("refresh: 1; url = profile.php");
                                                    }
                                                    else {
                                                        echo "<p class='error'> Email is already taken. </p>";
                                                    }
                                                }
                                            }
                                            else {
                                                echo "<p class='error'> Current Password Incorrect. </p>";
                                            }
                                        } 
                                        else {
                                            echo "<p class='error'> New password must not be the old password. </p>";
                                        }
                                    } 
                                    else {
                                        echo "<p class='error'> Current Password Incorrect. </p>";
                                    }
                                }
                            }
                        ?>

                        <a class="return" href="profile.php"> Cancel </a>
                    </div>
                </div>
            </form>
        </section>
    </main>
</body>
</html>