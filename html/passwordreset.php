<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <link rel="stylesheet" href="../css/passwordreset.css">
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
            <form method='POST'>
            <?php
                $token = $_GET["token"];
                $tokenHash = hash("sha256", $token);

                $findUser = "SELECT * FROM users WHERE resetToken='$tokenHash'";
                $userResult = mysqli_query($conn, $findUser);
                $user = mysqli_fetch_assoc($userResult);
                
                if ($user===NULL) {
                    echo "<h1 class='error'> Token not found. </h1>";
                } 
                else {
                    if(strtotime($user["resetTokenExpire"])<=time()){
                        echo "<h1 class='error'> Token expired. </h1>";
                    }
                    else {
                        echo"
                                <h1> RESET PASSWORD </h1>
                                <input type='password' name='newPassword' minlength='8' placeholder='New Password' required>
                                <input type='password' name='confirmPassword' minlength='8' placeholder='Confirm Password' required>
                                <input type='submit' name='submit' value='Recover'>
                            ";

                            if(isset($_POST["submit"])){
                                $newPassword = $_POST["newPassword"];
                                $confirmPassword = $_POST["confirmPassword"];
                                $userId = $user["profileId"];
                                $encryptPassword = password_hash($confirmPassword, PASSWORD_BCRYPT);

                                if ($newPassword==$confirmPassword){
                                    $recoverUser = "UPDATE users SET profilePassword='$encryptPassword', resetToken=NULL, resetTokenExpire=NULL WHERE profileId='$userId'";
                                    $recoverResult = mysqli_query($conn, $recoverUser);
                                    
                                    echo "<p class='success'><b>Account recovered successfully.</b></p>";
                                }
                                else {
                                    echo "<p class='error'> Passwords are not the same. </p>";
                                }
                            }
                    }
                }
            ?>
            <a class='return' href='login.php'> Go back to Login </a>
            </form>
        </section>
    </main>
</body>
</html>