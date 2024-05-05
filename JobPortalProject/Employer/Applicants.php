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
        
    
       
            
            ?>
        
        
        


   
        
        




<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Applicants</title>
        <link rel="stylesheet" type="text/css" href="css/Applicants.css">
        <link rel="stylesheet" type="text/css" href="css/empdash.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                            <li class="menu">
                                <a href="Dashboard.php"> 
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
                        <li class="active1">
                            <a href="Applicants.php" class="active2">
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
                            <h1>Applicants</h1>
                        </div>
                        <!--userImg-->  
                        <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?> 
                    </div>
                    <div class="details">
                        <div class="Applicants">
                            <div class="ApplicantsHeader">
                                <h2><ion-icon name="document-text-outline"></ion-icon>Applicants</h2>
                            <div class="textheader"><div class="text">There Are <a href="#"><?php echo $totalapplications ?></a> Applicants</div>
                            <div class="all"><a href="#">See All</a></div>
                        </div>
                        </div>
                     
                                    
                        <div class="cardbox">
                            <?php
                             // Query to fetch details of applicants
                $queryApplicants = "SELECT app.jobseeker_id, job.jobtitle, js.first_name, js.last_name, js.profile_picture, js.experience, js.skills, js.availability, app.application_id
                FROM applications AS app
                INNER JOIN joblistings AS job ON app.job_id = job.job_id
                INNER JOIN jobseekerprofiles AS js ON app.jobseeker_id = js.user_id
                WHERE app.status=0 AND app.job_id <> 1 AND job.employer_id = ?";
                $stmtApplicants = mysqli_prepare($conn, $queryApplicants);
                mysqli_stmt_bind_param($stmtApplicants, "i", $employerId);
                mysqli_stmt_execute($stmtApplicants);
                $resultApplicants = mysqli_stmt_get_result($stmtApplicants);

                            
                            // Display the data
                    while ($row = mysqli_fetch_assoc($resultApplicants)) {
                        $applicationId = $row['application_id'];
                        $jobseekerId = $row['jobseeker_id'];
                        $jobtitle = $row['jobtitle'];
                        $firstname = $row['first_name'];
                        $lastname = $row['last_name'];
                        $profilepicture = $row['profile_picture'];
                        $experience = $row['experience'];
                        $skills = $row['skills'];
                        $availability = $row['availability'];
                            ?>
                          
                                <div class="card" data-href="ApplicantDetails.php?user_id=<?php echo $jobseekerId; ?>&application_id=<?php echo $applicationId; ?>">
                                    <div class="company">
                                    <?php echo '<div class="user"><img src="' . $profilepicture . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?> 
                                    </div>
                                    <div class="Candname">
                                        <h4><?php echo $firstname.' '.$lastname?></h4></div>
                                        <div class="Jobtitle">Applied For : <a href="#" style="font-weight : 600"><?php echo $jobtitle?></a></div> 
                                   
                                    <div class="Candskills">
                                        <div class="viewskill"><h4><h4>View Skills</h4></h4>
                                        <div class="Skillbox">
                                            <div><?php echo $skills?>
                                            </div>
                                            </div>
                                    </div>
                                        <span><?php echo $availability?></span>
                                     
                                    </div>
                                </div> 
                               
                               <?php
                               echo '</a>';
                               ?>
              <?php                   
        }
    
   
    
        ?>
                               
                                        
                  
                        </div>
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
            //seeall
            const viewAllButton = document.querySelector('.all');
             const appliedJobs = document.querySelector('.Applicants');
             viewAllButton.addEventListener('click', function(event) {
             event.preventDefault(); 
             appliedJobs.classList.toggle('active');
            });
           // Get all the cards
            const cards = document.querySelectorAll('.card');

            // Attach a click event listener to each card
            cards.forEach(card => {
                card.addEventListener('click', function() {
                    // Get the target URL from the data-href attribute
                    const targetUrl = card.getAttribute('data-href');
                    
                    // Navigate to the target URL
                    window.location.href = targetUrl;
                });
            });
               // SweetAlert2
 if (window.location.search.indexOf('applicantselected') > -1) {
                Swal.fire({
                icon: 'success',
                title: 'Applicant Selected Succesfully!',
                showConfirmButton: false,
                timer: 1500
            });
            setTimeout(function () {
                window.location.href = 'Applicants.php'; // Redirect to the final target page
            }, 1600); // Adjust the delay time as needed
        }
//rejected
        if (window.location.search.indexOf('applicantrejected') > -1) {
                Swal.fire({
                icon: 'success',
                title: 'Applicant Rejected Succesfully!',
                showConfirmButton: false,
                timer: 1500
            });
            setTimeout(function () {
                window.location.href = 'Applicants.php'; // Redirect to the final target page
            }, 1600); // Adjust the delay time as needed
        }
    //failed
        if (window.location.search.indexOf('selectionfailed') > -1) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'There Was An Error Selecting The Application, Please Try Again Later!',
            timer: 1500
        });
        setTimeout(function () {
            window.location.href = 'Applicants.php'; // Redirect to the final target page
        }, 1600); // Adjust the delay time as needed
}
            </script>
        </body>
</html>