<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/register.css">

</head>
<body>
    <div class="logoCase">
        <img class="logoImage" src="../images/Logo/artourlogo.png" onclick="resetZoom()"/>
    </div>

    <div class="hide">
    
    <div>
    <?php
        session_start();

        $DBHost = "localhost";
        $DBUser = "root";
        $DBPass = "";
        $DBName = "artourdb";
        $conn = mysqli_connect($DBHost, $DBUser, $DBPass, $DBName);
    ?>

    <main>
        <section class="form">
            <form method="POST">

                <div class="title">
                    <h1> Register </h1>
                </div>
                
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Full Name" required>

                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Email Address" required>

                <label for="address">Address</label>
                <input type="text" id="address" name="address" placeholder="Address" required>

                <label for="number">Contact Number</label>
                <input type="text" id="number" name="number" maxlength="11" placeholder="Contact Number" required>

                <label for="password">Password</label>
                <input type="password" id="password" minlength="8" name="password" placeholder="Password" required>

                <label for="password">Confirm Password</label>
                <input type="password" id="confirmPassword" minlength="8" name="confirmPassword" placeholder="Confirm Password" required>
                
                <input type="submit" name="submit" value="Register">

                <?php 
                    if(isset($_POST["submit"])){
                        $name = $_POST["name"];
                        $email = $_POST["email"];
                        $address = $_POST["address"];
                        $number = $_POST["number"];

                        $password = $_POST["password"];
                        $confirmPassword = $_POST["confirmPassword"];
                        $encryptPassword = password_hash($confirmPassword, PASSWORD_BCRYPT);

                        if($password==$confirmPassword){
                            $isExist = "SELECT * FROM users WHERE profileEmail='$email'";
                            $checkExist = mysqli_query($conn, $isExist);
                            $row = mysqli_num_rows($checkExist);
    
                            if ($row == 0) {
                                $token = bin2hex(random_bytes(16));
                                $tokenHash = hash("sha256", $token);
                                $tokenExpire = date("Y-m-d H:i:s", time() + 60 * 30);

                                $registerUser = "INSERT INTO users (profileType, profileName, profilePassword, profileAddress, profileEmail, profileNumber, profileFacebook, profileInstagram, profileX, profilePicture, dateCreated, activeStatus, verifyStatus, verifyToken, verifyTokenExpire) 
                                VALUES (2, '$name', '$encryptPassword', '$address', '$email', '$number', 'Not Available', 'Not Available', 'Not Available', 'default.jpg', curdate(), 1, 0, '$tokenHash', '$tokenExpire')";
                                $addUser = mysqli_query($conn, $registerUser);

                                $mail = require __DIR__ . "/mailer.php";
                                $mail->setFrom("noreply@artour.com", "ArTour");
                                $mail->addAddress($email);
                                $mail->Subject = "Verify Account";
                                $mail->Body = <<<END
                                Click <a href="http://localhost/artour/html/verifyaccount.php?token=$token">here</a> to verify your account.
                                END;
    
                                try{
                                    $mail->send();
                                    echo "<p class='success'> Please check your inbox to verify registration. </p>";
                                }
                                catch (Exception $e) {
                                    echo "<p class='error'> Message could not be sent. Mailer error: {$mail->ErrorInfo}</p>";
                                }
                            }
                            else {
                                echo "<p class='error'> Email address already taken. </p>";
                            } 
                        }
                        else {
                            echo "<p class='error'> Passwords are not the same. </p>";
                        }
                    }
                ?>

                <a class="return" href="login.php"> Go back to Login </a>
            </form>
        </section>
    </main>
    </div>
</body>
</html>