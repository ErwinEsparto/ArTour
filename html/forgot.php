<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../css/forgot.css">
</head>
<body>
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
                    <h1> Forgot Password </h1>
                </div>
                
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Email Address" required>
                
                <input type="submit" name="submit" value="Recover">

                <?php 
                    if(isset($_POST["submit"])){
                        $email = $_POST["email"];

                        $recoverUser = "SELECT * FROM users WHERE profileEmail='$email'";
                        $result = mysqli_query($conn, $recoverUser);
                        
                        if (mysqli_num_rows($result) == 1) {
                            $token = bin2hex(random_bytes(16));
                            $tokenHash = hash("sha256", $token);
                            $tokenExpire = date("Y-m-d H:i:s", time() + 60 * 30);

                            $addToken = "UPDATE users SET resetToken='$tokenHash', resetTokenExpire='$tokenExpire' WHERE profileEmail='$email'";
                            $tokenResult = mysqli_query($conn, $addToken);

                            $mail = require __DIR__ . "/mailer.php";
                            $mail->setFrom("noreply@artour.com", "ArTour");
                            $mail->addAddress($email);
                            $mail->Subject = "Password Reset";
                            $mail->Body = <<<END
                            Click <a href="http://localhost/artour/html/passwordreset.php?token=$token">here</a> to reset your password.
                            END;

                            try{
                                $mail->send();
                                echo "<p class='success'> Please check your inbox to recover password. </p>";
                            }
                            catch (Exception $e) {
                                echo "<p class='error'> Message could not be sent. Mailer error: {$mail->ErrorInfo}</p>";
                            }
                        } 
                        else {
                            echo "<p class='error'> Email not found. </p>";
                        }
                    }
                ?>

                <a class="return" href="login.php"> Go back to Login </a>
            </form>
        </section>
    </main>
</body>
</html>