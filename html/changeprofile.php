<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Profile</title>
    <link rel="stylesheet" href="../css/changeprofile.css">
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

        $loggedIn = $_SESSION['loggedIn'] ?? false;

        $sessionExpiration = 1800;
        if ($loggedIn==true){
            if (isset($_SESSION['latestActivity']) && (time() - $_SESSION['latestActivity']) > $sessionExpiration) {
                header("Location: logout.php");
                exit();
            }
            $_SESSION['latestActivity'] = time();
        }

        if($_SESSION['userType']!=2){
            header("location: home.php");
        }
    ?>

    <header>
        <div class="mainheader">
            <div class="logo">
                <h1><a href="home.php"> ArTour </a></h1>
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
                    <a href="#"> Upload </a>
                    <a href="notifications.php"> Notifications </a>
                    <a href="chats.php"> Chats </a>
                    <a href="home.php"> Home </a>
                    <a class="button" href="#divOne"> Logout </a>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <section class="form">
            <form method="POST" enctype="multipart/form-data">
                <div class="column">
                    <div class="profile">
                        <a class="profileLink" href="profile.php"><img src="../profiles/<?php echo $account['profilePicture']; ?>"  alt=""></a>
                        <div class="details">
                            <input type="text" id="name" name="name" value="<?php echo $account['profileName']; ?>" readonly>
                            <input type="text" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" readonly>
                        </div>
                    </div>

                    <img id="uploadedimage" alt="Selected Image">

                    <label for="image">Attach a file</label>
                    <input type="file" id="uploadimage" name="image" accept="image/*" required>
                    
                    <input type="submit" name="submit" value="Update">

                    <?php 
                        if(isset($_POST["submit"])){
                            $imageFile = $_FILES["image"]["name"];
                            $date = date('mdY');
                            $fileExtension = pathinfo($imageFile, PATHINFO_EXTENSION);
                            $newImageFile = $account['profileName']. "_".$date . "_" .time(). "." . $fileExtension;
                            $userId = $account["profileId"];

                            $imageTempName = $_FILES["image"]["tmp_name"];
                            $folder = "../profiles/";

                            if ($fileExtension=='jpg' || $fileExtension=="JPG" || $fileExtension=="png" || $fileExtension=="PNG" ){
                                $uploadImg = "UPDATE users SET profilePicture='$newImageFile' WHERE profileId='".$_SESSION['userId']."'";
                                $saveProfile = mysqli_query($conn, $uploadImg);

                                $notifQuery = "INSERT INTO notifications (notifierId, notifType, notifiedId, notifyDate, readStatus)
                                    VALUES ('".$_SESSION['userId']."', 6, '".$_SESSION['userId']."', now(), 1)";
                                $notif = mysqli_query ($conn, $notifQuery);
    
                                $imageFilePath = $folder . $newImageFile;
                                if (move_uploaded_file($imageTempName, $imageFilePath)) {
                                    echo "<p class='success'> Changed successfully. </p>";
                                    header("refresh: 1; url = profile.php");
                                } else {
                                    echo "<p class='error'> Failed to Upload </p>";
                                }
                            }
                            else {
                                echo "<p class='error'> Only JPG/PNG images allowed. </p>";
                            }
                        }
                    ?>

                    <a class="return" href="profile.php"> Cancel </a>
                </div>
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
    <script>
        const uploadimage = document.getElementById('uploadimage');
        const uploadedimage = document.getElementById('uploadedimage');
        const imageSection = document.getElementById('imageSection');
        uploadimage.onchange = evt => {
            const [file] = uploadimage.files;
            if (file) {
                uploadedimage.src = URL.createObjectURL(file);
                uploadedimage.style.display = 'block';
                imageSection.style.display = 'flex';
            }
        };
    </script>
</body>
</html>