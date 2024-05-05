<?php
include('../config.php');
// Fetch the profile picture URL from the database
        $employerUsername = $_SESSION['username'];
        $query = "SELECT logo FROM employerprofiles WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $employerUsername);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $profilePictureURL);
     

        
  
        // Fetch the result and close the statement
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        $employerUsername = $_SESSION['username'];
        $query = "SELECT employer_id, company_name, email, logo, contact_number, location, website, about_us, links, industry FROM employerprofiles WHERE username = '$employerUsername'";
        $result = mysqli_query($conn, $query);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $employer_id = $row['employer_id'];
            $company_name = $row['company_name'];
            $email = $row['email'];
            $logo = $row['logo'];
            $contact_number = $row['contact_number'];
            $location = $row['location'];
            $website = $row['website'];
            $about_us = $row['about_us'];
            $links = $row['links'];
            $industry = $row['industry'];


        ?>
        
        
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile</title>
        <link rel="stylesheet" type="text/css" href="css/Profile.css">
        <link rel="stylesheet" type="text/css" href="css/empdash.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
        <link rel="stylesheet" type="text/css" href="css/logout.css">
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
                            <a href="PostJobs.php">
                            <span class="icon"> <ion-icon name="paper-plane-outline"></ion-icon></span>
                        <span class="nav-item">Post Jobs</span>
                        </a>
                        </li>
                        <li class="menu">
                            <a href="PostedJobs.php">
                            <span class="icon"> <ion-icon name="documents-outline"></ion-icon></span>
                        <span class="nav-item">Posted Jobs</span>
                        </a>
                        </li>
                        <li class="menu">
                            <a href="CandidateListings.php">
                            <span class="icon"> <ion-icon name="list-outline"></ion-icon> </i></span>
                        <span class="nav-item">Candidate Listings</span>
                        </a>
                        </li>
                        <li class="menu">
                            <a href="Applicants.php">
                            <span class="icon"><ion-icon name="document-text-outline"></ion-icon> </span>
                        <span class="nav-item">Applicants</span>
                        </a>
                        </li>
                        <li class="menu">
                            <a href="SelectedCandidates.php">
                            <span class="icon"><ion-icon name="checkmark-done-outline"></ion-icon> </span>
                        <span class="nav-item">Selected Candidates</span>
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
                        <span class="nav-item">Feedback</span>
                        </a>
                        </li>
                    
                    
                        <li class="menu">
                            <a href="#" class="logout" onclick="ShowCustomConfirm()">
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
                            <tr>
                                        <th>Company Name</th>
                                        <td style="position:relative; top:1px;"><?php echo $company_name; ?> </td>
                                </tr>
                                <tr>
                                        <th>Email</th>
                                        <td><?php echo $email; ?></td>
                                </tr>
                                <tr>
                                        <th>Contact Number</th>
                                        <td><?php echo $contact_number; ?></td>
                                </tr>
                                </tr> <tr>
                                    <th>Location</th>
                                    <td><?php echo $location; ?></td>
                                </tr>
                                <tr>
                                <th>Website</th>
                                    <td style="position:relative; top:-15px;">
                                        <div class="multiline-links">
                                        <?php
                                                // Split the links by line break and create anchor tags
                                                $linksArray1 = explode("\n", $website);
                                                foreach ($linksArray1 as $website1) {
                                                echo "<a href='$website1'>$website1</a><br>";
                                                }
                                                ?>
                                    </div>
                                    </td>
                                </tr>
                                <tr>
                                <th>About Us</th>
                                    <td><textarea readonly><?php echo $about_us; ?>
                                    </textarea></td>
                                </tr>
                               
                                <tr>
                                <th>Links</th>
                                    <td style="position:relative; top:-15px;">
                                        <div class="multiline-links">
                                        <?php
                                                // Split the links by line break and create anchor tags
                                                $linksArray = explode("\n", $links);
                                                foreach ($linksArray as $link) {
                                                echo "<a href='$link'>$link</a><br>";
                                                }
                                                ?>
                                    </div>
                                    </td>
                                </tr>
                            </table>
                            <?php
                        } else {
                                echo 'Job seeker not found.';
                            }
                        ?>
                            <div class="btns">
                            <div class="edit"><a href="EditProfile.php"  style="position: relative; top : 40px;">Edit <ion-icon name="create-outline"></ion-icon></a></div>&nbsp &nbsp
                           
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

            //sweetalert    
        if (window.location.search.indexOf('profileupdated') > -1) {
                        Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Profile Updated Successfully',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    setTimeout(function () {
                        window.location.href = 'Profile.php'; // Redirect to the final target page
                    }, 1600); // Adjust the delay time as needed
                }
        //sweetalert    
        if (window.location.search.indexOf('passwordupdated') > -1) {
                Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Password Updated Successfully',
                showConfirmButton: false,
                timer: 1500
            });
            setTimeout(function () {
                window.location.href = 'Profile.php'; // Redirect to the final target page
            }, 1600); // Adjust the delay time as needed
        }

            </script>
        </body>
</html>