<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/login.css">
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
                    <h1> LOGIN </h1>
                </div>
                
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Email Address" required>

                <label for="password"> Password </label>
                <input type="password" id="password" name="password" placeholder="Password" required>
                
                <div class="condition">
                    <div class="remember">
                        
                    </div>
                    <div class="forgot">
                        <a href="forgot.php"> Forgot Password? </a>
                    </div>
                </div>
                
                <input type="submit" name="submit" value="Login">

                <?php 
                    if(isset($_POST["submit"])){
                        $email = $_POST["email"];
                        $password = $_POST["password"];

                        $getUser = "SELECT * FROM users WHERE profileEmail='$email' AND profilePassword='$password';";
                        $userResult = mysqli_query($conn, $getUser);

                        if (mysqli_num_rows($userResult) == 1) {
                            $user=mysqli_fetch_assoc($userResult);

                            $_SESSION['userId'] = $user['profileId'];
                            $_SESSION['loggedIn'] = true;

                            header("location: home.php");
                            exit();
                        } 
                        else {
                            echo "<p class='result'> Invalid credentials. </p>";
                        }
                    }
                ?>

                <hr> <p class="register"> Don't have an account? <a href="register.php"> <b> Register </b> </a> </p>
            </form>
        </section>
    </main>
</body>
</html>