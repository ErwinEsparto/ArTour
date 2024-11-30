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
    ?>

    <header>
        <div class="mainheader">
            <div class="logo">
                <h1><a href="home.php"> ArTour </a></h1>
            </div>

            <div class="search">
                <img src="../images/searchicon.png" alt="">
                <input type="search" id="search" name="search" placeholder="Search">
            </div>
        
            <div class="navigation">
                <nav class="sections">
                    <a class="active" href="#"> Upload an Image </a>
                    <a href="home.php"> Home </a>
                    <a class="button" href="logout.php"> Logout </a>
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
                            $newImageFile = pathinfo($imageFile, PATHINFO_FILENAME) . "_" . $date . "_" .time(). "." . $fileExtension;
                            $userId = $account["profileId"];

                            $imageTempName = $_FILES["image"]["tmp_name"];
                            $folder = "../profiles/";

                            if ($fileExtension=='jpg' || $fileExtension=="JPG" || $fileExtension=="png" || $fileExtension=="PNG" ){
                                $getOldImg = "SELECT profilePicture FROM users WHERE profileId='".$_SESSION['userId']."'";
                                $oldImageResult = mysqli_query($conn, $getOldImg);
                                $oldImage = mysqli_fetch_assoc($oldImageResult);

                                if($oldImage['profilePicture']!='default.jpg'){
                                    $oldImageLocation = '../profiles/'.$oldImage['profilePicture'];
                                    unlink($oldImageLocation);
                                }
                                
                                $uploadImg = "UPDATE users SET profilePicture='$newImageFile' WHERE profileId='".$_SESSION['userId']."'";
                                $saveProfile = mysqli_query($conn, $uploadImg);
    
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