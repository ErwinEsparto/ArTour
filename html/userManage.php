<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management</title>
    <link rel="stylesheet" href="../css/userManage.css">
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

        $getAdmins = "SELECT * FROM users WHERE profileType!=2 AND activeStatus!=0 AND profileId!='".$_SESSION['userId']."'";
        $adminsResult = mysqli_query($conn, $getAdmins);
        $adminsRow = mysqli_num_rows($adminsResult);

        $getInactiveAdmins = "SELECT * FROM users WHERE profileType!=2 AND activeStatus!=1";
        $inactiveAdminsResult = mysqli_query($conn, $getInactiveAdmins);
        $inactiveAdminsRow = mysqli_num_rows($inactiveAdminsResult);

        $getUsers = "SELECT * FROM users WHERE profileType!=1 AND activeStatus!=0";
        $usersResult = mysqli_query($conn, $getUsers);
        $usersRow = mysqli_num_rows($usersResult);

        $getBannedUsers = "SELECT * FROM users WHERE profileType!=1 AND activeStatus!=1";
        $bannedUsersResult = mysqli_query($conn, $getBannedUsers);
        $bannedUsersRow = mysqli_num_rows($bannedUsersResult);

        $sessionExpiration = 1800;
        if ($loggedIn==true){
            if (isset($_SESSION['latestActivity']) && (time() - $_SESSION['latestActivity']) > $sessionExpiration) {
                header("Location: logout.php");
                exit();
            }
            $_SESSION['latestActivity'] = time();
        }

        if(isset($_GET['deleteID'])){
            $userChats = "DELETE FROM chats WHERE messengerId='$_GET[deleteID]' OR receiverId='$_GET[deleteID]'";
            $deleteChats = mysqli_query ($conn, $userChats);

            $userComments = "DELETE FROM comments WHERE commentorId='$_GET[deleteID]'";
            $deleteComments = mysqli_query ($conn, $userComments);

            $userFollows = "DELETE FROM follow WHERE followerId='$_GET[deleteID]'";
            $deleteFollows = mysqli_query ($conn, $userFollows);

            $userFollowed = "DELETE FROM follow WHERE followedId='$_GET[deleteID]'";
            $deleteFollowed = mysqli_query ($conn, $userFollowed);

            $userLikes = "DELETE FROM likes WHERE profileId='$_GET[deleteID]'";
            $deleteLikes = mysqli_query ($conn, $userLikes);

            $findImages = "SELECT * FROM images WHERE userId='$_GET[deleteID]'";
            $findResult = mysqli_query($conn, $findImages);

            while ($image = mysqli_fetch_assoc($findResult)){
                $imageLocation = '../posts/'.$image['imageName'];

                if(file_exists($imageLocation)){
                    unlink($imageLocation);
                    
                    $query = "DELETE FROM images WHERE imageId='$image[imageId]'";
                    $delete = mysqli_query ($conn, $query);
    
                    $deleteCategory = "DELETE FROM categories WHERE imageId='$image[imageId]'";
                    $deleteC = mysqli_query ($conn, $deleteCategory);
    
                    $deleteLike = "DELETE FROM likes WHERE imageId='$image[imageId]'";
                    $deleteL = mysqli_query ($conn, $deleteLike);
                }
                else {
                    echo "<script type='text/javascript'>alert('Image Not Found!');</script>";
                    header("location:userManage.php");
                    die();
                }
            }

            $deleteUser = "DELETE FROM users WHERE profileId='$_GET[deleteID]'";
            $ripUser = mysqli_query ($conn, $deleteUser);
            header("location:userManage.php");
            die();
        }
        if(isset($_GET['activateId'])){
            $activateUser = "UPDATE users SET activeStatus=1 WHERE profileId='$_GET[activateId]'";
            $activateResult = mysqli_query ($conn, $activateUser);
            header("location:userManage.php");
            die();
        }
        if(isset($_GET['deactivateId'])){
            $deactivateUser = "UPDATE users SET activeStatus=0 WHERE profileId='$_GET[deactivateId]'";
            $deactivateResult = mysqli_query ($conn, $deactivateUser);
            header("location:userManage.php");
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
                            <a href="reports.php"> Reports </a>
                            <a class="active" href="#"> Accounts </a>
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
        <div class="adminContainer">
            <div class="table">
                <div class="adminSection">
                <h1> Registered Admins </h1>
                <a href="addAdmin.php"> Add more </a>
                </div>
                <table class="reports">
                    <thead>
                        <tr class="columns">
                            <th>Admin ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if($adminsRow>0){
                            while ($inactiveAdmin = mysqli_fetch_assoc($adminsResult)){
                                echo "
                                <tr class='user'>
                                    <td>".$inactiveAdmin['profileId']." </td>
                                    <td>".$inactiveAdmin['profileName']."</td>
                                    <td>".$inactiveAdmin['profileEmail']."</td>
                                    <td> <a class='disable' href='javascript:void()' onClick='disAlert(".$inactiveAdmin['profileId'].")'> Deactivate </a> |
                                    <a class='disable' href='javascript:void()' onClick='delAlert(".$inactiveAdmin['profileId'].")'> Delete </a> </td>
                                </tr>";
                            }
                        }
                        else {
                            echo "<tr><td colspan='6'> You are the only admin. </td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="table">
                <h1> Inactive Admins </h1>
                <table class="reports">
                    <thead>
                        <tr class="columns">
                            <th>Admin ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if($inactiveAdminsRow>0){
                            while ($admin = mysqli_fetch_assoc($inactiveAdminsResult)){
                                echo "
                                <tr class='user'>
                                    <td>".$admin['profileId']." </td>
                                    <td>".$admin['profileName']."</td>
                                    <td>".$admin['profileEmail']."</td>
                                    <td> <a class='disable' href='javascript:void()' onClick='actAlert(".$admin['profileId'].")'> Activate </a> |
                                    <a class='disable' href='javascript:void()' onClick='delAlert(".$admin['profileId'].")'> Delete </a> </td>
                                </tr>";
                            }
                        }
                        else {
                            echo "<tr><td colspan='6'> No Inactive Admins </td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="userSection">
            <div class="table">
            <h1> Active Users </h1>
                <table class="reports">
                    <thead>
                        <tr class="columns">
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if($usersRow>0){
                            while ($user = mysqli_fetch_assoc($usersResult)){
                                echo "
                                <tr class='user'>
                                    <td>".$user['profileId']." </td>
                                    <td>".$user['profileName']."</td>
                                    <td>".$user['profileEmail']."</td>
                                    <td> <a class='disable' href='javascript:void()' onClick='disAlert(".$user['profileId'].")'> Deactivate </a> |
                                    <a class='disable' href='javascript:void()' onClick='delAlert(".$user['profileId'].")'> Delete </a> </td>
                                </tr>";
                            }
                        }
                        else {
                            echo "<tr><td colspan='6'> No Active Users </td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="table">
            <h1> Banned Users </h1>
                <table class="reports">
                    <thead>
                        <tr class="columns">
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if($bannedUsersRow>0){
                            while ($bannedUser = mysqli_fetch_assoc($bannedUsersResult)){
                                echo "
                                <tr class='user'>
                                    <td>".$bannedUser['profileId']." </td>
                                    <td>".$bannedUser['profileName']."</td>
                                    <td>".$bannedUser['profileEmail']."</td>
                                    <td> <a class='enable' href='javascript:void()' onClick='actAlert(".$bannedUser['profileId'].")'> Activate </a> |
                                    <a class='disable' href='javascript:void()' onClick='delAlert(".$bannedUser['profileId'].")'> Delete </a> </td>
                                </tr>";
                            }
                        }
                        else {
                            echo "<tr><td colspan='6'> No Banned Users </td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
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
            sts = confirm ("Are you sure you want to deactivate this account?");
            if (sts){
                document.location.href=`userManage.php?deactivateId=${id}`;
            }
        }
        function actAlert(id){
            sts = confirm ("Are you sure you want to activate this account?");
            if (sts){
                document.location.href=`userManage.php?activateId=${id}`;
            }
        }
        function delAlert(id){
            sts = confirm ("Are you sure you want to delete this user?");
            if (sts){
                document.location.href=`userManage.php?deleteID=${id}`;
            }
        }
</script>
</html>