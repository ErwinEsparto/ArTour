<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/register.css">
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
                    <h1> Register </h1>
                </div>
                
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Full Name" required>

                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Email Address" required>

                <label for="address">Address</label>
                <input type="text" id="address" name="address" placeholder="Address" required>

                <label for="number">Contact Number</label>
                <input type="text" id="number" name="number" placeholder="Contact Number" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required>
                
                <input type="submit" name="submit" value="Register">

                <?php 
                    if(isset($_POST["submit"])){
                        $name = $_POST["name"];
                        $email = $_POST["email"];
                        $address = $_POST["address"];
                        $number = $_POST["number"];
                        $password = $_POST["password"];

                        $registerUser = "INSERT INTO users (profileName, profilePassword, profileAddress, profileEmail, profileNumber, profileFacebook, profileInstagram, profileX, profilePicture, dateCreated) 
                        VALUES ('$name', '$password', '$address', '$email', '$number', 'Not Available', 'Not Available', 'Not Available', 'default.jpg', curdate())";
                        $addUser = mysqli_query($conn, $registerUser);
                        echo "<p class='result'> Successfully Registered. </p>";
                    }
                ?>

                <a class="return" href="login.php"> Go back to Login </a>
            </form>
        </section>
    </main>
</body>
</html>