
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

        // Fetch the employer's ID using the username
        $queryEmployerId = "SELECT employer_id FROM employerprofiles WHERE username = ?";
        $stmtEmployerId = mysqli_prepare($conn, $queryEmployerId);
        mysqli_stmt_bind_param($stmtEmployerId, "s", $employerUsername);
        mysqli_stmt_execute($stmtEmployerId);
        mysqli_stmt_bind_result($stmtEmployerId, $employerId);
        mysqli_stmt_fetch($stmtEmployerId);
        mysqli_stmt_close($stmtEmployerId);


        $countQuery = "SELECT COUNT(*) AS totalNotifications FROM notifications WHERE employer_id = $employerId";
        $result1 = mysqli_query($conn, $countQuery);

        if ($result1) {
            $row = mysqli_fetch_assoc($result1);
            $totalNotifications = $row['totalNotifications'];
        } else {
            $totalNotifications = 0; // Error handling if the query fails
        }


       // Query to get the count of total applications
       $queryTotalApplications = "SELECT COUNT(*) AS totalapplications, app.jobseeker_id
       FROM applications AS app
       INNER JOIN joblistings AS job ON app.job_id = job.job_id
       WHERE app.status=0 AND app.job_id <> 1 AND job.employer_id = ?";
       $stmtTotalApplications = mysqli_prepare($conn, $queryTotalApplications);
       mysqli_stmt_bind_param($stmtTotalApplications, "i", $employerId);
       mysqli_stmt_execute($stmtTotalApplications);
       $resultTotalApplications = mysqli_stmt_get_result($stmtTotalApplications);
       $rowTotalApplications = mysqli_fetch_assoc($resultTotalApplications);
       $totalapplications = $rowTotalApplications['totalapplications'];
       
         // Query to get the count of total applications
         $queryselectedApplications = "SELECT COUNT(*) AS selectedApplications
         FROM applications AS app
         INNER JOIN joblistings AS job ON (app.job_id = job.job_id )
         WHERE app.status = 1 AND app.employer_id = ?";
         $stmtselectedApplications = mysqli_prepare($conn, $queryselectedApplications);
         mysqli_stmt_bind_param($stmtselectedApplications, "i",  $employerId);
         mysqli_stmt_execute($stmtselectedApplications);
         $resultselectedApplications = mysqli_stmt_get_result($stmtselectedApplications);
         $rowselectedApplications = mysqli_fetch_assoc($resultselectedApplications);
         $selectedApplications = $rowselectedApplications['selectedApplications'];
 
         $queryselectedApplications1 = "SELECT COUNT(*) AS selectedApplications1
         FROM applications AS app
         INNER JOIN joblistings AS job ON (app.job_id = job.job_id AND job.employer_id = ?)
         WHERE app.status = 1 AND app.employer_status <> 1 OR app.employer_id = ?";
         $stmtselectedApplications1 = mysqli_prepare($conn, $queryselectedApplications1);
         mysqli_stmt_bind_param($stmtselectedApplications1, "ii",  $employerId, $employerId);
         mysqli_stmt_execute($stmtselectedApplications1);
         $resultselectedApplications1 = mysqli_stmt_get_result($stmtselectedApplications1);
         $rowselectedApplications1 = mysqli_fetch_assoc($resultselectedApplications1);
         $selectedApplications1 = $rowselectedApplications1['selectedApplications1'];
         
         $selectedApplications =  $selectedApplications+ $selectedApplications1;
        
          // Query to get the count of total applications
       $queryTotaljobs = "SELECT COUNT(*) AS totaljobs
       FROM joblistings
       WHERE employer_id = ?";
       $stmtTotaljobs = mysqli_prepare($conn, $queryTotaljobs);
       mysqli_stmt_bind_param($stmtTotaljobs, "i", $employerId);
       mysqli_stmt_execute($stmtTotaljobs);
       $resultTotaljobs = mysqli_stmt_get_result($stmtTotaljobs);
       $rowTotaljobs = mysqli_fetch_assoc($resultTotaljobs);
       $totaljobs = $rowTotaljobs['totaljobs'];

            $query = "SELECT COUNT(application_id) AS NumberOfRejectedCandidates
        FROM applications AS app
        INNER JOIN joblistings AS job ON app.job_id = job.job_id
        WHERE app.status = 2 AND job.employer_id = ?";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $employerId); // Bind the employerId as an integer
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Check if there are any results
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $numberOfRejectedCandidates = $row['NumberOfRejectedCandidates'];
        }


        ?>
        
        
        
        <!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="stylesheet" type="text/css" href="css/Dashboard.css">
        <link rel="stylesheet" type="text/css" href="css/empdash.css">
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
                                <a href="Dashboard.php" class="active2" > 
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
                            <div class="numbers"><?php echo $totaljobs?></div>
                            <div class="cardname">Active Job Postings</div>
                        </div>
                        <div class="iconbx">
                            <ion-icon name="eye-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="card">
                        <div>
                            <div class="numbers"><?php echo $totalapplications?></div>
                            <div class="cardname">Applicants</div>
                        </div>
                        <div class="iconbx">
                            <ion-icon name="document-text-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="card">
                        <div>
                            <div class="numbers"><?php echo $selectedApplications?></div>
                            <div class="cardname">Selected Applicants</div>
                        </div>
                        <div class="iconbx">
                            <ion-icon name="checkmark-done-outline"></ion-icon>
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
                            <h2>Active Job Postings</h2>
                            <a href="#" class="btn">View All</a>
                        </div>
                      <div class="tablecontainer">
                        <table>
                            <thead>
                                <tr>
                                    <td>Job Title</td>
                                    <td>Job Level</td>
                                    <td>Posted Date</td>
                                    <td>Number Of Applicants</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT
                                job.jobtitle AS JobTitle,
                                job.joblevel AS JobLevel,
                                job.posteddate AS PostedDate,
                                COUNT(app.application_id) AS NumberOfApplicants
                            FROM joblistings AS job
                            LEFT JOIN applications AS app ON job.job_id = app.job_id
                            WHERE job.employer_id = ?
                            GROUP BY job.job_id";
                            
                            $stmt = mysqli_prepare($conn, $query);
                            mysqli_stmt_bind_param($stmt, "i", $employerId); // Bind the employerId as an integer
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            
                            // Check if there are any results
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $jobTitle = $row['JobTitle'];
                                    $jobLevel = $row['JobLevel'];
                                    $postedDate = $row['PostedDate'];
                                    $numberOfApplicants = $row['NumberOfApplicants'];
                            
                                    // Output the data in your HTML table
                                    echo '<tr>';
                                    echo '<td>' . $jobTitle . '</td>';
                                    echo '<td>' . $jobLevel . '</td>';
                                    echo '<td>' . $postedDate . '</td>';
                                    echo '<td><span class="applicantnumber">' . $numberOfApplicants . '</span></td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="4">No job listings found.</td></tr>';
                            }
                            
                            // Close the database connection
                            mysqli_stmt_close($stmt);
                                ?>
                               
                                
                            </tbody>
                        </table>
                      </div>
                                    <?php
                                    echo '<script>';
                                        echo 'var activeJobPostings = ' . $totaljobs . ';';
                                        echo 'var totalApplicants = ' . $totalapplications . ';';
                                        echo 'var selectedApplicants = ' . $selectedApplications . ';';
                                        echo 'var rejectedApplicants = ' . $numberOfRejectedCandidates . ';';
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