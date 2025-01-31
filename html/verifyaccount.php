<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Account</title>
    <link rel="stylesheet" href="../css/verifyaccount.css">
</head>
<body>
    <?php
        session_start();

        $DBHost = "localhost";
        $DBUser = "root";
        $DBPass = "";
        $DBName = "artourdb";
        $conn = mysqli_connect($DBHost, $DBUser, $DBPass, $DBName);

        if(!isset($_GET['token'])){
            header('location: login.php');
            die();
        }
    ?>
    <main>
        <section class="form">
            <form method='POST'>
            <?php
                $token = $_GET["token"];
                $tokenHash = hash("sha256", $token);

                $findUser = "SELECT * FROM users WHERE verifyToken='$tokenHash'";
                $userResult = mysqli_query($conn, $findUser);
                $user = mysqli_fetch_assoc($userResult);
                
                if ($user===NULL) {
                    echo "<h1 class='error'> Token not found. </h1>";
                } 
                else {
                    if(strtotime($user["verifyTokenExpire"])<=time()){
                        echo "<h1 class='error'> Token expired. </h1>";
                    }
                    else {
                        $userId = $user["profileId"];
                        $verifyUser = "UPDATE users SET verifyStatus=1, verifyToken=NULL, verifyTokenExpire=NULL WHERE profileId='$userId'";
                        $verifyUser = mysqli_query($conn, $verifyUser);
                        
                        echo "<h1 class='success'><b>Account Verified!</b></h1>";
                    }
                }
            ?>
            <a class='return' href='login.php'> Go back to Login </a>
            </form>
        </section>
    </main>
</body>
</html>