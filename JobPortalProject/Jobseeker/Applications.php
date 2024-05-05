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


        $SeekerUsername = $_SESSION['username'];
        $querySeekerId = "SELECT user_id FROM jobseekerprofiles WHERE username = ?";
        $stmtSeekerId = mysqli_prepare($conn, $querySeekerId);
        mysqli_stmt_bind_param($stmtSeekerId, "s", $SeekerUsername);
        mysqli_stmt_execute($stmtSeekerId);
        mysqli_stmt_bind_result($stmtSeekerId, $SeekerId);
        mysqli_stmt_fetch($stmtSeekerId);
        mysqli_stmt_close($stmtSeekerId);

        // Query to count the total users in jobseekerprofiles
        $countQuery = "SELECT COUNT(*) AS totalapplications FROM applications WHERE jobseeker_id= $SeekerId AND job_id <> 1";
        $result1 = mysqli_query($conn, $countQuery);

        if ($result1) {
            $row = mysqli_fetch_assoc($result1);
            $totalapplications = $row['totalapplications'];
        }

        $query = "SELECT * FROM applications WHERE jobseeker_id = ? AND job_id <> 1";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $SeekerId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        
        


      


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Applications</title>
        <link rel="stylesheet" type="text/css" href="css/Applications.css">
        <link rel="stylesheet" type="text/css" href="css/seekerdash.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                            <li class="menu">
                                <a href="DashboardSection.php" > 
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
                        <li class="active1">
                                <a href="Applications.php" class="active2" > 
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
                
                <div class="main">
                    <div class="topbar">
                        <div class="toggle">
                            <ion-icon name="menu-outline"></ion-icon>
                        </div>
                        <div class="topname">
                            <h1>Applications</h1>
                        </div>
                        <!--userImg-->  
                        <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?> 
                    </div>
                    <div class="details">
                        <div class="Feedback">
                            <div class="FeedbackHeader">
                                <h2><ion-icon name="documents-outline"></ion-icon> Applications</h2>
                            <div class="textheader"><div class="text">You Have Applied For <a href="#"><?php  echo $totalapplications;?></a> Jobs</div>
                            <div class="all"><a href="#" id="deleteLink">Clear All</a></div>
                        </div>
                        <div class="cardbox">
                        <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Extract application details from $row and display them in a card
                            $applicationId = $row['application_id'];
                            $jobId = $row['job_id'];
                            $date = $row['application_date'];

                            // Query to fetch job title and company name based on job ID
                            $query = "SELECT j.jobtitle, e.company_name
                                FROM joblistings j
                                INNER JOIN employerprofiles e ON j.employer_id = e.employer_id
                                WHERE j.job_id = ?";

                            $stmt = mysqli_prepare($conn, $query);
                            mysqli_stmt_bind_param($stmt, "i", $jobId);
                            mysqli_stmt_execute($stmt);
                            $result2 = mysqli_stmt_get_result($stmt);

                            if ($row2 = mysqli_fetch_assoc($result2)) {
                                $jobTitle = $row2['jobtitle'];
                                $companyName = $row2['company_name'];
                            }

                            // Display the card with application details
                            ?>
                            <div class="card">
                                <div class="message"><a href="#">Application Submitted for <?php echo $jobTitle; ?> at <?php echo $companyName; ?></a></div>
                                <div class="View"><a href="ViewApplication.php?application_id=<?php echo $applicationId; ?>">View Application</a></div>
                                <div class="date">Applied on : <?php echo $date; ?></div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "No Applications found.";
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
if (window.location.search.indexOf('applicationdeleted') > -1) {
                Swal.fire({
                icon: 'success',
                title: 'Application Deleted Successfully',
                showConfirmButton: false,
                timer: 1500
            });
            setTimeout(function () {
                window.location.href = 'Applications.php'; // Redirect to the final target page
            }, 1600); // Adjust the delay time as needed
        }

        //sweetalert
if (window.location.search.indexOf('allapplicationscleared') > -1) {
                Swal.fire({
                icon: 'success',
                title: 'Application Deleted Successfully',
                showConfirmButton: false,
                timer: 1500
            });
            setTimeout(function () {
                window.location.href = 'Applications.php'; // Redirect to the final target page
            }, 1600); // Adjust the delay time as needed
        }
        
//clearAll
document.addEventListener("DOMContentLoaded", function () {
    var deleteLink = document.getElementById("deleteLink");

    deleteLink.addEventListener("click", function (e) {
        e.preventDefault(); // Prevent the default link behavior

        Swal.fire({
            title: 'Are You Sure You Want To Delete All Applications?',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Yes, Clear it',
            cancelButtonText: 'No, keep it',
        }).then((result) => {
            if (result.isConfirmed) {
                // User confirmed, proceed with deletion
                var form = document.createElement("form");
                form.method = "POST";
                form.action = "ClearApplications.php";

                // Append the form to the document body
                document.body.appendChild(form);

                form.submit();
            }
        });
    });
});

   

  </script>
        </body>
        </html>