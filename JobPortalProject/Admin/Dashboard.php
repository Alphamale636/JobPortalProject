<?php
include('../config.php');

// Fetch 5 most recent job seekers
$queryJobSeekers = "SELECT `username`, `joineddate`, `last_login` FROM `jobseekerprofiles` ORDER BY `joineddate` DESC LIMIT 10";
$resultJobSeekers = mysqli_query($conn, $queryJobSeekers);

// Fetch 5 most recent employers
$queryEmployers = "SELECT `username`, `joineddate`, `last_login` FROM `employerprofiles` WHERE employer_id<>1 ORDER BY `joineddate` DESC LIMIT 10";
$resultEmployers = mysqli_query($conn, $queryEmployers);

// Count the total number of jobseeker profiles
$queryJobSeekerCount = "SELECT COUNT(*) FROM jobseekerprofiles";
$resultJobSeekerCount = mysqli_query($conn, $queryJobSeekerCount);
if ($resultJobSeekerCount) {
    $rowJobSeekerCount = mysqli_fetch_row($resultJobSeekerCount);
    $totalJobSeekerProfiles = $rowJobSeekerCount[0];
} else {
    $totalJobSeekerProfiles = 0; // Error handling if the query fails
}


// Count the total number of employer profiles
$queryEmployerCount = "SELECT COUNT(*) FROM employerprofiles";
$resultEmployerCount = mysqli_query($conn, $queryEmployerCount);
if ($resultEmployerCount) {
    $rowEmployerCount = mysqli_fetch_row($resultEmployerCount);
    $totalEmployerProfiles = $rowEmployerCount[0];
} else {
    $totalEmployerProfiles = 0; // Error handling if the query fails
}

// Count the rows in the joblistings table
$query = "SELECT COUNT(*) AS total_jobs FROM joblistings";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalJobs = $row['total_jobs'];
} else {
    // Handle the error if the query fails
    $totalJobs = 0; // Set a default value
}

//notification count
    if (isset($_SESSION['username'])) {
    $adminUsername = $_SESSION['username'];

    // Query to count notifications for the admin user
    $countQuery = "SELECT COUNT(*) AS notification_count FROM notifications WHERE user_id IN (SELECT admin_id FROM adminprofiles WHERE username = ?)";

    $stmt = mysqli_prepare($conn, $countQuery);
    mysqli_stmt_bind_param($stmt, "s", $adminUsername);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $notificationCount);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
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



?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="stylesheet" type="text/css" href="css/Dashboard.css">
        <link rel="stylesheet" type="text/css" href="css/Admindash.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                            <li class="active1">
                                <a href="Dashboard.php" class="active2"> 
                        <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
                        <span class="nav-item">Dashboard</span>
                        </a>
                        </li>
                        <li class="menu">
                                <a href="Profile.php"> 
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
                            <a href="#" class="logout" onclick="ShowCustomConfirm()">
                            <span class="icon"><ion-icon name="log-out-outline"></ion-icon> </span>
                        <span class="nav-item">Logout</span>
                        </a>
                        </li>
                        </ul>
                    </div>
                   
                    
                </div>
                <!--main-->               
                <div class="main">
                    <div class="topbar">
                        <div class="toggle">
                            <ion-icon name="menu-outline"></ion-icon>
                        </div>
                        <div class="topname">
                            <h1>Dashboard</h1>
                        </div>
                        <!--userImg-->  
                        <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?> 
                    </div>
                
                    <div class="cardbox">
                        <div class="card">
                         <div>
                            <div class="numbers"> <?php echo $totalEmployerProfiles + $totalJobSeekerProfiles;?></div>
                            <div class="cardname">Total Users</div>
                        </div>
                        <div class="iconbx">
                            <ion-icon name="person-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="card">
                        <div>
                            <div class="numbers"><?php echo $totalJobSeekerProfiles;?></div>
                            <div class="cardname">Job Seekers</div>
                        </div>
                        <div class="iconbx">
                            <ion-icon name="people-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="card">
                        <div>
                            <div class="numbers"> <?php echo $totalEmployerProfiles;?></div>
                            <div class="cardname">Employers</div>
                        </div>
                        <div class="iconbx">
                            <ion-icon name="people"></ion-icon>
                        </div>
                    </div>
                    <div class="card">
                        <div>
                            <div class="numbers"><?php echo $totalJobs;?></div>
                            <div class="cardname">Job Listings</div>
                        </div>
                        <div class="iconbx">
                            <ion-icon name="list-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="card">
                        <div>
                            <div class="numbers"><?php echo $notificationCount?></div>
                            <div class="cardname">Notifications</div>
                        </div>
                        <div class="iconbx">
                            <ion-icon name="notifications-outline"></ion-icon>
                        </div>
                    </div>
                </div>
                <div class="details">
                    <div class="Recentusers">
                        <div class="cardheader">
                            <h2>Recent Users</h2>
                            <a href="#" class="btn">View All</a>
                        </div>
                        <div class="tablecontainer">
                        <table>
                            <thead>
                                <tr>
                                    <td>User Name</td>
                                    <td>Account Type</td>
                                    <td>Joined Date</td>
                                    <td>Last Login </td>
                                </tr>
                                
                            </thead>
                            <tbody>
                                
                            <?php

    // Loop through the recent job seekers
                            while ($row = mysqli_fetch_assoc($resultJobSeekers)) {
                                $username = $row['username'];
                                $joinedDate = $row['joineddate'];
                                $lastLogin = $row['last_login'];

                                // Display the job seeker data
                                echo "<tr>";
                                echo "<td>$username</td>";
                                echo "<td>Jobseeker</td>"; // Set account type as Jobseeker
                                echo "<td>$joinedDate</td>";
                                echo "<td>$lastLogin</td>";
                                echo "</tr>";
                            }
    // Loop through the recent employers
                        while ($row = mysqli_fetch_assoc($resultEmployers)) {
                            $username = $row['username'];
                            $joinedDate = $row['joineddate'];
                            $lastLogin = $row['last_login'];

                            // Display the employer data
                            echo "<tr>";
                            echo "<td>$username</td>";
                            echo "<td>Employer</td>"; // Set account type as Employer
                            echo "<td>$joinedDate</td>";
                            echo "<td>$lastLogin</td>";
                            echo "</tr>";
                        }
                            ?>
                               
                            
                            </tbody>
                        </table>
                        </div>
                    
                    </div>
<?php
//passing values to chart
echo '<script>';
echo 'var totalUsers = ' . $totalEmployerProfiles + $totalJobSeekerProfiles . ';';
echo 'var employerCount = ' .$totalEmployerProfiles . ';';
echo 'var jobseekerCount = ' .$totalJobSeekerProfiles . ';';
echo 'var joblistingsCount = ' .$totalJobs . ';';
// Repeat for other data you want to pass
echo '</script>';
?>
                    <div class="Graphcard">
                        
                            <canvas class="chart" id="dashboardchart"></canvas>
                        
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
        <script src="js/dashboardchard.js"></script>
        <script src="js/highlightnavigation.js"></script>
        <script src="js/logout.js"></script>
        <script>
            
            //MenuToggle
            let toggle = document.querySelector('.toggle');
            let navigation = document.querySelector('.navigation');
            let main = document.querySelector('.main');

            toggle.onclick = function(){
                navigation.classList.toggle('active');
                main.classList.toggle('active');
            }

            //viewall
             const viewAllButton = document.querySelector('.btn ');
             const appliedJobs = document.querySelector('.Recentusers');
             viewAllButton.addEventListener('click', function(event) {
             event.preventDefault(); 
             appliedJobs.classList.toggle('active');
            });

        </script>
        </body>
</html>
