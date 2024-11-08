<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image</title>
    <link rel="stylesheet" href="../css/uploadimage.css">
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
                        <a class="profileLink" href="profile.php"><img src="../profiles/<?php echo $account['profilePicture']; ?>"  alt=""> </a>
                        <div class="details">
                            <input type="text" id="name" name="name" value="<?php echo $account['profileName']; ?>" readonly>
                            <input type="text" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" readonly>
                        </div>
                    </div>

                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Type something..."></textarea>

                    <label for="category"> Choose Image Category: </label>
                    <div class="selectCategories">
                        <select id="category1" name="category1">
                            <option value="Abstract">Abstract</option>
                            <option value="Animal">Animal</option>
                            <option value="Architecture">Architecture</option>
                            <option value="Digital">Digital</option>
                            <option value="Drawing">Drawing</option>
                            <option value="Game">Game</option>
                            <option value="Nature">Nature</option>
                            <option value="Painting">Painting</option>
                            <option value="Photography">Photography</option>
                            <option value="Sculpture">Sculpture</option>
                        </select>
                        <select id="category2" name="category2">
                            <option value="None">None</option>
                            <option value="Abstract">Abstract</option>
                            <option value="Animal">Animal</option>
                            <option value="Architecture">Architecture</option>
                            <option value="Digital">Digital</option>
                            <option value="Drawing">Drawing</option>
                            <option value="Game">Game</option>
                            <option value="Nature">Nature</option>
                            <option value="Painting">Painting</option>
                            <option value="Photography">Photography</option>
                            <option value="Sculpture">Sculpture</option>
                        </select>
                        <select id="category3" name="category3">
                            <option value="None1">None</option>
                            <option value="Abstract">Abstract</option>
                            <option value="Animal">Animal</option>
                            <option value="Architecture">Architecture</option>
                            <option value="Digital">Digital</option>
                            <option value="Drawing">Drawing</option>
                            <option value="Game">Game</option>
                            <option value="Nature">Nature</option>
                            <option value="Painting">Painting</option>
                            <option value="Photography">Photography</option>
                            <option value="Sculpture">Sculpture</option>
                        </select>
                    </div>

                    <label for="image">Attach a file</label>
                    <input type="file" id="uploadimage" name="image" accept="image/*" placeholder="Address" required>
                    
                    <input type="submit" name="submit" value="Upload">

                    <?php 
                        if(isset($_POST["submit"])){
                            $imageFile = $_FILES["image"]["name"];
                            $imageDescription = htmlspecialchars(trim($_POST['description']));
                            $imageCategory1 = $_POST["category1"];
                            $imageCategory2 = $_POST["category2"];
                            $imageCategory3 = $_POST["category3"];
                            $userId = $account["profileId"];

                            $imageTempName = $_FILES["image"]["tmp_name"];
                            $folder = "../posts/".$imageFile;

                            if ($imageCategory1!=$imageCategory2 && $imageCategory1!=$imageCategory3 && $imageCategory2 != $imageCategory3) {
                                $uploadImg = "INSERT INTO images (imageName, imageDescription, uploadDate, userId) VALUES ('$imageFile', '$imageDescription', NOW(), '$userId')";
                                mysqli_query($conn, $uploadImg);

                                $getUploadedImg = "SELECT MAX(imageId) AS imageId FROM images";
                                $uploadedImgResult = mysqli_query($conn, $getUploadedImg);
                                $uploadedImg = mysqli_fetch_assoc($uploadedImgResult);

                                $addCategory1 = "INSERT INTO categories (imageId, category) VALUES (".(int)$uploadedImg['imageId'].", '$imageCategory1')";
                                mysqli_query($conn, $addCategory1);

                                if ($imageCategory2!="None") {
                                    $addCategory2 = "INSERT INTO categories (imageId, category) VALUES (".(int)$uploadedImg['imageId'].", '$imageCategory2')";
                                    mysqli_query($conn, $addCategory2);
                                }

                                if ($imageCategory3!="None1"){
                                    $addCategory3 = "INSERT INTO categories (imageId, category) VALUES (".(int)$uploadedImg['imageId'].", '$imageCategory3')";
                                    mysqli_query($conn, $addCategory3);
                                }
                                
                                if (move_uploaded_file($imageTempName, $folder)) {
                                    echo "<p class='success'> Uploaded successfully. </p>";
                                } else {
                                    echo "<p class='error'> Failed to Upload </p>";
                                }
                            }
                            else {
                                echo "<p class='error'> Please differentiate each category. </p>";
                            }
                        }
                    ?>

                    <a class="return" href="home.php"> Cancel </a>
                </div>
                <div id="imageSection" class="column">
                    <img id="uploadedimage" alt="Selected Image">
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