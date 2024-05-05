<?php
include('../config.php');
            if (isset($_SESSION['username'])) {
            $adminUsername = $_SESSION['username'];
            $query = "SELECT * FROM adminprofiles WHERE username = '$adminUsername'";
            $result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $name = $row['fullname'];
    $email = $row['email'];
    $contactNumber = $row['contact_number'];
    $officeAddress = $row['office_address'];
    $aboutMe = $row['about_me'];
    $links = $row['links'];
}
}

// Fetch the profile picture URL from the database
        $adminUsername = $_SESSION['username'];
        $query = "SELECT profile_picture FROM adminprofiles WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $adminUsername);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $profilePictureURL);

        // Fetch the result and close the statement
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $contactNumber = $_POST['contact-number'];
        $officeAddress = $_POST['officeaddress'];
        $aboutMe = $_POST['aboutme'];

        $links = implode("\n", (array)$_POST['links']);
        $adminUsername = $_SESSION['username'];

        if ($_FILES['profilepicture']['error'] === 0) {
            $uploadDir = '../uploads/'; 
            $uploadFile = $uploadDir . basename($_FILES['profilepicture']['name']);
            
            if (move_uploaded_file($_FILES['profilepicture']['tmp_name'], $uploadFile)) {
                // File upload successful
                $profilepicture = $uploadFile;
            } else {
                // File upload failed
                $profilepicture = $profilePictureURL; // You can set a default image or handle this case accordingly
            }
        } else {
            // No file uploaded or an error occurred
            $profilepicture = $profilePictureURL; // You can set a default image or handle this case accordingly
        }

        // Prepare the UPDATE query
        $query = "UPDATE adminprofiles SET `fullname` = ?, `email` = ?, `contact_number` = ?, `office_address` = ?, `about_me` = ?, `links` = ?, `profile_picture` = ? WHERE `username` = ?";

        if ($stmt = mysqli_prepare($conn, $query)) {
            // Bind the parameters and execute the query
            
            mysqli_stmt_bind_param($stmt, "ssssssss", $name, $email, $contactNumber, $officeAddress, $aboutMe, $links, $profilepicture, $adminUsername);

            if (mysqli_stmt_execute($stmt)) {
                // Data updated successfully
                header('Location: Profile.php?profileupdated=1');
            } else {
                // Failed to update data
                echo "Error: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt);
        } else {
            // Database query error
            echo "Database query error. Please try again later.";
        }
    }

        mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile</title>
        <link rel="stylesheet" type="text/css" href="css/Profile.css">
        <link rel="stylesheet" type="text/css" href="css/Admindash.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
        <link rel="stylesheet" type="text/css" href="css/logout.css">
        <style>
            table td{
                min-height: 50px;
            }
            input
            {
                position: relative;
                top:-5px;
            }
        </style>
        </head>
        <body>
            <div class="container">
                <div class="navigation">
                        <div class="logo">
                            <img src="../images/RECRUIT_LOGO_2.png" alt="">
                            <h1>Recruit</h1>
                        </div>
                        <ul>
                            <li class="menu">
                                <a href="Dashboard.php"> 
                        <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
                        <span class="nav-item">Dashboard</span>
                        </a>
                        </li>
                            <li class="active1">
                                <a href="Profile.php" class="active2"> 
                        <span class="icon"><ion-icon name="person-outline"></ion-icon></span>
                        <span class="nav-item">Profile</span>
                        </a>
                        </li>
                        <li class="menu">
                            <a href="Jobseekermanage.php">
                            <span class="icon"> <ion-icon name="people-outline"></ion-icon> </i></span>
                        <span class="nav-item">Manage Job Seekers</span>
                        </a>
                        </li>
                        <li class="menu">
                            <a href="EmployerManage.php">
                            <span class="icon"><ion-icon name="people"></ion-icon> </span>
                        <span class="nav-item">Manage Employers</span>
                        </a>
                        </li>
                        <li class="menu">
                            <a href="JobListingsManage.php">
                            <span class="icon"><ion-icon name="list-outline"></ion-icon> </span>
                        <span class="nav-item">Manage Job Listings</span>
                        </a>
                        </li>
                       
                        <li class="menu">
                            <a href="Notifications.php">
                            <span class="icon"><ion-icon name="notifications-outline"></ion-icon> </span>
                        <span class="nav-item">Notifications</span>
                        </a>
                        </li>
                        <li class="menu">
                            <a href="Feedback.php"><span class="icon"><ion-icon name="chatbubble-outline"></ion-icon></span>
                        <span class="nav-item">Feedbacks</span>
                        </a>
                        </li>
                        <li class="menu">
                            <a href="ReportGeneration.php"><span class="icon"><ion-icon name="document-text-outline"></ion-icon></span>
                        <span class="nav-item">Report Generation</span>
                        </a>
                        </li>
                    
                    
                    
                        <li class="menu">
                            <a href=# class="logout" onclick="ShowCustomConfirm()">
                            <span class="icon"><ion-icon name="log-out-outline"></ion-icon> </span>
                        <span class="nav-item">Logout</span>
                        </a>
                        </li>
                        </ul>
                    </div>
                    
                
                <div class="main">
                    <div class="topbar">
                        <div class="toggle">
                            <ion-icon name="menu-outline"></ion-icon>
                        </div>
                        <div class="topname">
                            <h1>Profile</h1>
                        </div>
                        <!--userImg-->  
                        <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?>
                    </div>
                    <div class="details">
                        <div class="Profile">
                            <div class="ProfileHeader">
                                <ion-icon name="person-outline"></ion-icon> <h2>PROFILE</h2>
                               <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?>
                            </div>
                            <table>
                                <form method="post" id="myForm" action="editprofile.php" enctype="multipart/form-data">
                           
                                    <tr>
                                        <th>Name</th>
                                        <td> <input type="text" class="inpbx" placeholder="Type Here.." name="name" style="outline: none; border: none; position:relative; top: 3px;" value="<?php echo $name; ?>"></td>
                                    </tr>
                                    <tr>
                                        <th>Profile Picture</th>
                                        <td> <input required="" type="file" name="profilepicture" accept="image/*" id="profilepicture" style="position:relative; width: 190px; top:-5px; overflow:hidden;"></td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td><input type="text" class="inpbx" placeholder="Type Here.." name="email" style="outline: none; border: none" value="<?php echo $email; ?>"></td>
                                    </tr>
                                    <tr>
                                        <th>Contact Number</th>
                                        <td><input type="text" class="inpbx" placeholder="Type Here.." name="contact-number" style="outline: none; border: none" value="<?php echo $contactNumber; ?>"></td>
                                    </tr>
                                </tr>
                                 <tr>
                                    <th>Office Address</th>
                                    <td><textarea class="inpbx" placeholder="Type Here.." name="officeaddress"><?php echo $officeAddress; ?></textarea></td>
                                </tr>
                                <tr>
                                    <th>About Me</th>
                                    <td><textarea class="inpbx" placeholder="Type Here.." name="aboutme"><?php echo $aboutMe; ?></textarea></td>
                                     </textarea></td>
                                </tr>
                                <tr>
                                    <th>Links</th>
                        
                                    <td><textarea class="inpbx" placeholder="Type Here.." name="links"><?php 
                                    $linksArray = explode("\n", $links);
                                    foreach ($linksArray as $link) {
                                    echo "$link";
                                    }
                                    ?>
                                    </textarea></td>
                                    
                                </tr>
                              
                                </form>
                            </table>
                            <div class="btns" style="position:relative; top:-5px">
                            <div class="edit" id="submit" ><a href="#"><ion-icon name="checkmark-outline" style="font-size:1.2em; top:3px; left:-2px;"></ion-icon>Save</a></div>&nbsp &nbsp
                            <div class="edit"><a href="profile.php" ><ion-icon name="close-outline" style="font-size:1.2em; top:4px; left: -2px;"></ion-icon>Cancel</a></div>&nbsp &nbsp
                            <div class="password"><a href="Passchange.php">Change Password <ion-icon name="create-outline"></ion-icon></a></div>
                            </div>
                        </div>
                    </div>
                    <div id="overlay" class="overlay"></div>
                    <div id="custom-confirm" class="model" style="display:none">
                        <div class="model-content">
                            <div class="confirmationtext">
                            <p>Are you sure you want to logout?</p>
                            </div>
                            <div class="buttoncontainer">
                            <button id="yes-button">Yes</button>
                            <button id="no-button">No</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
        <script src="js/logout.js"></script>
        <script src="js/highlightnavigation.js"></script>
        <script>
    //MenuToggle
            let toggle = document.querySelector('.toggle');
            let navigation = document.querySelector('.navigation');
            let main = document.querySelector('.main');

            toggle.onclick = function(){
                navigation.classList.toggle('active');
                main.classList.toggle('active');
            }
            
    // trigger the submit button
            document.getElementById("submit").addEventListener("click", function(e) {
                e.preventDefault();
                document.getElementById("myForm").submit();
            });
            </script>

        </body>
</html>