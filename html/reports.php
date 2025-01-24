<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="../css/reports.css">
</head>
<body>

    <?php
        session_start();

        $DBHost = "localhost";
        $DBUser = "root";
        $DBPass = "";
        $DBName = "artourdb";
        $conn = mysqli_connect($DBHost, $DBUser, $DBPass, $DBName);
        $loggedIn = $_SESSION['loggedIn'] ?? false;
        $selectedCategory = (empty($_GET['category'])) ? 'All' : '';

        $getReports = "SELECT * FROM images INNER JOIN users 
        ON images.userId=users.profileId WHERE reportStatus=1";
        $reportResult = mysqli_query($conn, $getReports);
        $rownum = mysqli_num_rows($reportResult);

        if(isset($_GET['deleteID'])){
            $findImage = "SELECT * FROM images WHERE imageId='$_GET[deleteID]'";
            $findResult = mysqli_query($conn, $findImage);
            $image = mysqli_fetch_assoc($findResult);

            $imageLocation = $_DIR_.'../posts/'.$image['imageName'];

            if(file_exists($imageLocation)){
                unlink($imageLocation);
                
                $query = "DELETE FROM images WHERE imageId='$_GET[deleteID]'";
                $delete = mysqli_query ($conn, $query);

                $deleteCategory = "DELETE FROM categories WHERE imageId='$_GET[deleteID]'";
                $deleteC = mysqli_query ($conn, $deleteCategory);

                $deleteLike = "DELETE FROM likes WHERE imageId='$_GET[deleteID]'";
                $deleteL = mysqli_query ($conn, $deleteLike);

                header("location:reports.php");
                die();
            }
            else {
                echo "<script type='text/javascript'>alert('Image Not Found!');</script>";
                header("location:reports.php");
                die();
            }
        }
    ?>

    <header>
        <div class="mainheader">
            <div class="logo">
                <h1> ArTour </h1>
            </div>

            <div class="search">
                <img src="../images/searchicon.png" alt="">
                <input type="search" id="search" name="search" placeholder="Search">
            </div>
        
            <div class="navigation">
                <nav class="sections">
                <?php
                    if ($loggedIn == true && $_SESSION['userType']==2){
                        echo '  
                            <a href="uploadimage.php"> Upload </a>
                            <a href="notifications.php"> Notifications </a>
                            <a href="chats.php"> Chats </a>
                            <a href="profile.php"> Profile </a>
                            <a class="button" href="logout.php"> Logout </a>
                        ';
                    }
                    else if ($loggedIn == true && $_SESSION['userType']==1){
                        echo '  
                            <a class="active" href="reports.php"> Reports </a>
                            <a href="home.php"> Home </a>
                            <a class="button" href="logout.php"> Logout </a>
                        ';
                    }
                    else {
                        echo '
                            <a href="login.php"> Upload an Image </a>
                            <a class="button" href="login.php"> Login </a>
                        ';
                    }
                ?>
                </nav>
            </div>
        </div>
    </header>
    <main>
    <div class="table">
            <table class="reports">
                <thead>
                    <tr class="columns">
                        <th>Image ID</th>
                        <th>Uploader</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Link</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if($rownum>0){
                        while ($report = mysqli_fetch_assoc($reportResult)){
                            echo "
                            <tr class='report'>
                                <td>".$report['imageId']." </td>
                                <td>".$report['profileName']."</td>
                                <td>".$report['imageDescription']." </td>
                                <td>".$report['uploadDate']." </td>
                                <td><a class='enable' target='_blank' href='viewpost.php?post=".$report['imageId']."'>View</a></td>
                                <td> <a class='disable' href='javascript:void()' onClick='disAlert(".$report['imageId'].")'> Unreport </a> |
                                <a class='disable' href='javascript:void()' onClick='delAlert(".$report['imageId'].")'> Delete </a> </td>
                            </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
<script>
        function disAlert(id){
            sts = confirm ("Are you sure you want to unreport this post?");
            if (sts){
                document.location.href=`removeReport.php?reportId=${id}`;
            }
        }
        function delAlert(id){
            sts = confirm ("Are you sure you want to delete this post?");
            if (sts){
                document.location.href=`reports.php?deleteID=${id}`;
            }
        }
</script>
</html>