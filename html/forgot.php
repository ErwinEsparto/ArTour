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

                <label for="number"> Contact Number </label>
                <input type="text" id="number" name="number" placeholder="Contact Number" required>

                <label for="newPassword"> New Password </label>
                <input type="password" id="newPassword" name="newPassword" placeholder="New Password" required>

                <label for="confirmPassword"> New Password </label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm New Password" required>
                
                <input type="submit" name="submit" value="Recover">

                <?php 
                    if(isset($_POST["submit"])){
                        $email = $_POST["email"];
                        $number = $_POST["number"];
                        $newPassword = $_POST["newPassword"];
                        $confirmPassword = $_POST["confirmPassword"];

                        $recoverUser = "SELECT * FROM users WHERE profileEmail='$email' AND profileNumber='$number'";
                        $result = mysqli_query($conn, $recoverUser);
                        
                        if ($newPassword != $confirmPassword) {
                            echo "<p class='error'> New passwords are not the same. </p>";
                        }
                        else if (mysqli_num_rows($result) == 1) {
                            $user=mysqli_fetch_assoc($result);
                            $profileId = $user["profileId"];

                            mysqli_query($conn, "UPDATE users SET profilePassword='$confirmPassword' WHERE profileId='$profileId'");
                            echo "<p class='success'> Account recovered successfully. </p>";
                        } 
                        else {
                            echo "<p class='error'> Account not found. </p>";
                        }
                    }
                ?>

                <a class="return" href="login.php"> Go back to Login </a>
            </form>
        </section>
    </main>
</body>
</html>