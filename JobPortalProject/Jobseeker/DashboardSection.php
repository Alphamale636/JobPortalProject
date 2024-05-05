<?php
include('../config.php');
// Fetch the profile picture URL from the database
        $seekerUsername = $_SESSION['username'];
        $query = "SELECT profile_picture FROM jobseekerprofiles WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $seekerUsername);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $profilePictureURL);
        
        // Fetch the result and close the statement
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);


   


        $sessionUsername = $_SESSION['username'];

        // Get the user_id for the session username
        $getUserIDQuery = "SELECT user_id FROM jobseekerprofiles WHERE username = ?";
        $stmt = mysqli_prepare($conn, $getUserIDQuery);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $sessionUsername);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $user_id);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
        } else {
            // Error handling if the first query fails
            $user_id = 0;
        }
        
       
            // Count the total applications for the user
            $queryCountApplications = "SELECT COUNT(*) AS totalApplications FROM applications WHERE jobseeker_id = ? AND job_id <> 1";
            $stmtCountApplications = mysqli_prepare($conn, $queryCountApplications);
        
            if ($stmtCountApplications) {
                mysqli_stmt_bind_param($stmtCountApplications, "i", $user_id);
                mysqli_stmt_execute($stmtCountApplications);
                mysqli_stmt_bind_result($stmtCountApplications, $totalApplications);
                mysqli_stmt_fetch($stmtCountApplications);
                mysqli_stmt_close($stmtCountApplications);
            } else {
                // Error handling if the second query fails
                $totalApplications = 0;
            }
    
            $queryCountStatus0 = "SELECT COUNT(*) AS totalStatus0 FROM applications WHERE jobseeker_id = ? AND `status` = 0";
            $stmtCountStatus0 = mysqli_prepare($conn, $queryCountStatus0);
    
            if ($stmtCountStatus0) {
                mysqli_stmt_bind_param($stmtCountStatus0, "i", $user_id);
                mysqli_stmt_execute($stmtCountStatus0);
                mysqli_stmt_bind_result($stmtCountStatus0, $totalStatus0);
                mysqli_stmt_fetch($stmtCountStatus0);
                mysqli_stmt_close($stmtCountStatus0);
            } else {
                // Error handling if the query for status = 0 fails
                $totalStatus0 = 0;
            }
            
            $queryCountStatus1 = "SELECT COUNT(*) AS totalStatus1 FROM applications WHERE jobseeker_id = ? AND status = 1 AND job_id <> 1";
            $stmtCountStatus1 = mysqli_prepare($conn, $queryCountStatus1);
    
            if ($stmtCountStatus1) {
                mysqli_stmt_bind_param($stmtCountStatus1, "i", $user_id);
                mysqli_stmt_execute($stmtCountStatus1);
                mysqli_stmt_bind_result($stmtCountStatus1, $totalStatus1);
                mysqli_stmt_fetch($stmtCountStatus1);
                mysqli_stmt_close($stmtCountStatus1);
            } else {
                // Error handling if the query for status = 1 fails
                $totalStatus1 = 0;
            }
       
            $queryRejectedCount = "SELECT COUNT(*) AS totalRejectedApplications FROM applications WHERE status = 2";
            $resultRejectedCount = mysqli_query($conn, $queryRejectedCount);

            if ($resultRejectedCount) {
                $rowRejectedCount = mysqli_fetch_assoc($resultRejectedCount);
                $totalRejectedApplications = $rowRejectedCount['totalRejectedApplications'];
            } else {
                $totalRejectedApplications = 0; // Error handling if the query fails
            }

           // Step 2: Count the total notifications for the job seeker
           $countQuery = "SELECT COUNT(*) AS totalNotifications FROM notifications WHERE jobseeker_id = $user_id";
           $result1 = mysqli_query($conn, $countQuery);

           if ($result1) {
               $row = mysqli_fetch_assoc($result1);
               $totalNotifications = $row['totalNotifications'];
           } else {
               $totalNotifications = 0; // Error handling if the query fails
           }
   
        
        
        
        
        $statusLabels = array(
            0 => 'Pending',
            1 => 'Selected',
            2 => 'Rejected'
        );
     

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="stylesheet" type="text/css" href="css/dashboardsection.css">
        <link rel="stylesheet" type="text/css" href="css/seekerdash.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
        <link rel="stylesheet" type="text/css" href="css/logout.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                                <a href="DashboardSection.php" class="active2" > 
                        <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
                        <span class="nav-item">Dashboard</span>
                        </a>
                        </li>
                            <li class="menu">
                                <a href="ProfileSection.php"> 
                        <span class="icon"><ion-icon name="person-outline"></ion-icon></span>
                        <span class="nav-item">Profile</span>
                        </a>
                        </li>
                        <li class="menu">
                            <a href="JobListings.php">
                            <span class="icon"> <ion-icon name="list-outline"></ion-icon> </i></span>
                        <span class="nav-item">Job Listings</span>
                        </a>
                        </li>
                        <li class="menu">
                            <a href="Resume.php">
                            <span class="icon"><ion-icon name="document-text-outline"></ion-icon> </span>
                        <span class="nav-item">Resume</span>
                        </a>
                        </li>
                        <li class="menu">
                                <a href="Applications.php" > 
                        <span class="icon"><ion-icon name="documents-outline"></ion-icon></span>
                        <span class="nav-item">Applications</span>
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
                            <a href=# class="logout" onclick="ShowCustomConfirm()">
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
                            <div class="numbers"><?php echo $totalApplications ?></div>
                            <div class="cardname">Applied Jobs</div>
                        </div>
                        <div class="iconbx">
                            <ion-icon name="paper-plane-outline"></ion-icon>
                        </div>
                    </div>
                        <div class="card">
                         <div>
                            <div class="numbers"><?php echo $totalStatus1 ?></div>
                            <div class="cardname">Selected Jobs</div>
                        </div>
                        <div class="iconbx">
                        <ion-icon name="checkmark-done-outline"></ion-icon>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div>
                            <div class="numbers"><?php echo $totalStatus0 ?></div>
                            <div class="cardname">Pending Jobs</div>
                        </div>
                        <div class="iconbx">
                        <ion-icon name="timer-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="card">
                        <div>
                            <div class="numbers"><?php echo  $totalNotifications?></div>
                            <div class="cardname">Notifications</div>
                        </div>
                        <div class="iconbx">
                            <ion-icon name="notifications-outline"></ion-icon>
                        </div>
                    </div>
                </div>
                <div class="details">
                    <div class="AppliedJobs">
                        <div class="cardheader">
                            <h2>Applied Jobs</h2>
                            <a href="#" class="btn">View All</a>
                        </div>
                        <div class="tablecontainer">
                        <table>
                            <thead>
                                <tr>
                                    <td>Job Title</td>
                                    <td>Company Name</td>
                                    <td>Application Date</td>
                                    <td>Status</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $getAppliedJobsQuery = "SELECT jl.jobtitle, ep.company_name, a.application_date, a.status
                                FROM applications a
                                INNER JOIN joblistings jl ON a.job_id = jl.job_id
                                INNER JOIN employerprofiles ep ON jl.employer_id = ep.employer_id
                                WHERE a.jobseeker_id = ? AND a.job_id <> 1";
                            $stmt = $conn->prepare($getAppliedJobsQuery);
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) {
                                $jobTitle = $row['jobtitle'];
                                $companyName = $row['company_name'];
                                $applicationDate = $row['application_date'];
                                $status = $row['status'];

                                $statusLabel = $statusLabels[$status];
                                echo '<tr>';
                                echo '<td>' . $jobTitle . '</td>';
                                echo '<td>' . $companyName . '</td>';
                                echo '<td>' . $applicationDate . '</td>';
                                echo '<td><span class="Status ' . $statusLabel . '">' . $statusLabel . '</span></td>';
                                echo '</tr>';
                            }
                      

                        $stmt->close();
                        $conn->close();

                    ?>
                                            

                        </table>
                       
                        </div>
                        <?php
//passing values to chart
echo '<script>';
echo 'var AppledJobs = ' . $totalApplications . ';';
echo 'var selectedCount = ' . $totalStatus1 . ';';
echo 'var rejectedCount = ' . $totalRejectedApplications . ';';
echo 'var pendingCount = ' . $totalStatus0 . ';';
echo '</script>';
?>
                    </div>
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
        <script src="js/logout.js"></script>
        <script src="js/highlightnavigation.js"></script>
        <script src="js/dashboardchart.js"></script>
     
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
             const appliedJobs = document.querySelector('.AppliedJobs');
             viewAllButton.addEventListener('click', function(event) {
             event.preventDefault(); 
             appliedJobs.classList.toggle('active');
            });

            </script>
        </body>
</html>