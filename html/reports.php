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

        $getImageReports = "SELECT * FROM reports JOIN users ON reports.reportedId=users.profileId 
        JOIN images ON images.imageId=reports.imageId
        WHERE reportType=1";
        $imageReportResult = mysqli_query($conn, $getImageReports);
        $rownum = mysqli_num_rows($imageReportResult);

        $getUserReports = "SELECT * FROM reports INNER JOIN users 
        ON reports.reportedId=users.profileId WHERE reportType=2";
        $userReportResult = mysqli_query($conn, $getUserReports);
        $userrownum = mysqli_num_rows($userReportResult);

        $sessionExpiration = 1800;
        if ($loggedIn==true){
            if (isset($_SESSION['latestActivity']) && (time() - $_SESSION['latestActivity']) > $sessionExpiration) {
                header("Location: logout.php");
                exit();
            }
            $_SESSION['latestActivity'] = time();
        }

        if(isset($_GET['deleteID'])){
            $query = "UPDATE images SET deleteStatus=1, reportStatus=0 WHERE imageId='$_GET[deleteID]'";
            $delete = mysqli_query ($conn, $query);
            header("location:reports.php");
            die();
        }
        if(isset($_GET['unreportId'])){
            $unreportUser = "DELETE FROM reports WHERE reportId='$_GET[unreportId]'";
            $unreportResult = mysqli_query ($conn, $unreportUser);
            header("location:reports.php");
            die();
        }
        if(isset($_SESSION['userId']) && $_SESSION['userType']!=2){
            echo"";
        }
        else {
            header("location:home.php");
            die();
        }
    ?>

    <header>
        <div class="mainheader">
            <div class="logo">
                <h1> ArTour </h1>
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
                <?php
                    if ($loggedIn == true && $_SESSION['userType']==2){
                        echo '  
                            <a href="uploadimage.php"> Upload </a>
                            <a href="notifications.php"> Notifications </a>
                            <a href="chats.php"> Chats </a>
                            <a href="profile.php"> Profile </a>
                            <a class="button" href="#divOne"> Logout </a>
                        ';
                    }
                    else if ($loggedIn == true && $_SESSION['userType']==1){
                        echo '  
                            <a class="active" href="reports.php"> Reports </a>
                            <a href="userManage.php"> Accounts </a>
                            <a href="home.php"> Home </a>
                            <a class="button" href="#divOne"> Logout </a>
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
            <h1> Reported Users </h1>
            <table class="reports">
                <thead>
                    <tr class="columns">
                        <th>Name</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Reason</th>
                        <th>Link</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if($userrownum>0){
                        while ($userReport = mysqli_fetch_assoc($userReportResult)){
                            echo "
                            <tr class='report'>
                                <td>".$userReport['profileName']."</td>
                                <td>".$userReport['profileAddress']." </td>
                                <td>".$userReport['profileEmail']." </td>
                                <td>".$userReport['reportReason']." </td>
                                <td><a class='enable' target='_blank' href='viewprofile.php?profile=".$userReport['reportId']."'>View</a></td>
                                <td> <a class='disable' href='javascript:void()' onClick='unReAlert(".$userReport['reportId'].")'> Unreport </a>
                            </tr>";
                        }
                    }
                    else {
                        echo "<tr><td colspan='6'> No Reported Users </td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="table">
            <h1> Reported Posts </h1>
            <table class="reports">
                <thead>
                    <tr class="columns">
                        <th>Uploader</th>
                        <th>Description</th>
                        <th>Reason</th>
                        <th>Date</th>
                        <th>Link</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if($rownum>0){
                        while ($report = mysqli_fetch_assoc($imageReportResult)){
                            echo "
                            <tr class='report'>
                                <td>".$report['profileName']."</td>
                                <td>".$report['imageDescription']." </td>
                                <td>".$report['reportReason']." </td>
                                <td>".$report['uploadDate']." </td>
                                <td><a class='enable' target='_blank' href='viewpost.php?post=".$report['imageId']."'>View</a></td>
                                <td> <a class='disable' href='javascript:void()' onClick='disAlert(".$report['reportId'].")'> Unreport </a> |
                                <a class='disable' href='javascript:void()' onClick='delAlert(".$report['reportId'].")'> Delete </a> </td>
                            </tr>";
                        }
                    }
                    else {
                        echo "<tr><td colspan='6'> No Reported Posts </td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
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
        function unReAlert(id){
            sts = confirm ("Are you sure you want to unreport this user?");
            if (sts){
                document.location.href=`reports.php?unreportId=${id}`;
            }
        }
</script>
</html>