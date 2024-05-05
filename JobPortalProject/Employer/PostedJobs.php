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
        $queryTotalJobs = "SELECT COUNT(*) AS TotalJobs
        FROM joblistings  
        WHERE employer_id = ?";
        $stmtTotalJobs = mysqli_prepare($conn, $queryTotalJobs);
        mysqli_stmt_bind_param($stmtTotalJobs, "i", $employerId);
        mysqli_stmt_execute($stmtTotalJobs);
        $resultTotalJobs = mysqli_stmt_get_result($stmtTotalJobs);
        $rowTotalJobs = mysqli_fetch_assoc($resultTotalJobs);
        $TotalJobs = $rowTotalJobs['TotalJobs'];
        
    
       
            
            ?>
        
        
        


   
        
        




<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Posted Jobs</title>
        <link rel="stylesheet" type="text/css" href="css/PostedJobs.css">
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
                        <li class="active1">
                            <a href="PostedJobs.php" class="active2">
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
                            <a href="Applicants.php" >
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
                            <h1>Posted Jobs</h1>
                        </div>
                        <!--userImg-->  
                        <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?> 
                    </div>
                    <div class="details">
                        <div class="Applicants">
                            <div class="ApplicantsHeader">
                                <h2><ion-icon name="documents-outline"></ion-icon>POSTED JOBS</h2>
                            <div class="textheader"><div class="text">You Have Posted <a href="#"><?php echo $TotalJobs ?></a> Jobs</div>
                            <div class="all"><a href="#">See All</a></div>
                        </div>
                        </div>
                     
                                    
                        <div class="cardbox">
                            <?php
                            $query = "SELECT jl.job_id, jl.jobtitle, jl.joblevel, jl.posteddate, ep.logo, jl.salary
                            FROM joblistings jl
                            INNER JOIN employerprofiles ep ON jl.employer_id = ep.employer_id
                            WHERE jl.employer_id = $employerId"; // You can add additional conditions if needed
                  
                  
                 


                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $jobId = $row['job_id'];
                            $jobtitle = $row['jobtitle'];
                            $joblevel = $row['joblevel'];
                            $logo = $row['logo'];
                            $salary = $row['salary'];
                            $postedDate = $row['posteddate']; 
                    
                            $postedDateTime = new DateTime($postedDate);
                            $currentDateTime = new DateTime();
                            $dateInterval = $currentDateTime->diff($postedDateTime);
                    
                            // Format the result as "X days ago"
                            if ($dateInterval->d == 0) {
                                $postedAgo = 'Today';
                            } elseif ($dateInterval->d == 1) {
                                $postedAgo = 'Yesterday';
                            } else {
                                $postedAgo = $dateInterval->d . ' days ago';
                            }
                
                            ?>
                        <?php
                         echo '<a href="PostedDetails.php?job_id=' . $jobId. '" style="text-decoration: none; color: black;">';
                        ?>
                           <div class="card">
                                        <div class="company">
                                            <?php
                                          
                                            echo '<div class="user"><img src="' . $logo . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';
                                            ?>
                                        </div>
                                        <div class="JobDescription">
                                            <h4><?php echo $jobtitle; ?></h4>
                                        </div>
                                        <div class="companypost">
                                      
                                        <div>Position : <?php echo $joblevel; ?></div> 
                                        </div>
                                        
                                            <div class="dateSalary">
                                                <h4><?php echo $salary; ?></h4>
                                                <span><?php echo"   "; echo $postedAgo;?></span>
                                            </div>
                                       
                                    </div>
                                    <?php
                                     echo '</a>';
                                   
                            }
                        
                        } else {
                            echo "No jobs found.";
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
                if (window.location.search.indexOf('jobdeleted') > -1) {
                Swal.fire({
                icon: 'success',
                title: 'Job Deleted Succesfully!',
                showConfirmButton: false,
                timer: 1500
            });
            setTimeout(function () {
                window.location.href = 'PostedJobs.php'; // Redirect to the final target page
            }, 1600); // Adjust the delay time as needed
        }
             
            </script>
        </body>
</html>