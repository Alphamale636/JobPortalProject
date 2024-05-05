<?php
include('../config.php');
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

//admin_id
        $adminUsername = $_SESSION['username'];
        $queryAdminId = "SELECT admin_id FROM adminprofiles WHERE username = ?";
        $stmtAdminId = mysqli_prepare($conn, $queryAdminId);
        mysqli_stmt_bind_param($stmtAdminId, "s", $adminUsername);
        mysqli_stmt_execute($stmtAdminId);
        mysqli_stmt_bind_result($stmtAdminId, $adminId);
        mysqli_stmt_fetch($stmtAdminId);
        mysqli_stmt_close($stmtAdminId);

        // Query to count the total users in jobseekerprofiles
        $countQuery = "SELECT COUNT(*) AS totalFeedbacks FROM feedbacks";
        $result1 = mysqli_query($conn, $countQuery);

        if ($result1) {
            $row = mysqli_fetch_assoc($result1);
            $totalFeedbacks = $row['totalFeedbacks'];
        }


        // Query to retrieve feedback
        $query = "SELECT f.feedback_id, f.feedbacktext, f.timestamp, f.jobseeker_id, f.employer_id,
        j.first_name AS jfirst_name, j.last_name AS jlast_name, e.company_name AS employer_name
        FROM feedbacks AS f
        LEFT JOIN jobseekerprofiles AS j ON f.jobseeker_id = j.user_id
        LEFT JOIN employerprofiles AS e ON f.employer_id = e.employer_id
        ORDER BY f.timestamp DESC"; // Adjust this query based on your database structure

            $result = mysqli_query($conn, $query);


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Feedback</title>
        <link rel="stylesheet" type="text/css" href="css/Feedback.css">
        <link rel="stylesheet" type="text/css" href="css/Admindash.css">
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
                            <li>
                                <a href="Dashboard.php"> 
                        <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
                        <span class="nav-item">Dashboard</span>
                        </a>
                        </li>
                            <li>
                                <a href="Profile.php"> 
                        <span class="icon"><ion-icon name="person-outline"></ion-icon></span>
                        <span class="nav-item">Profile</span>
                        </a>
                        </li>
                        <li>
                            <a href="Jobseekermanage.php">
                            <span class="icon"> <ion-icon name="people-outline"></ion-icon> </i></span>
                        <span class="nav-item">Manage Job Seekers</span>
                        </a>
                        </li>
                        <li>
                            <a href="EmployerManage.php">
                            <span class="icon"><ion-icon name="people"></ion-icon> </span>
                        <span class="nav-item">Manage Employers</span>
                        </a>
                        </li>
                        <li>
                            <a href="JobListingsManage.php">
                            <span class="icon"><ion-icon name="list-outline"></ion-icon> </span>
                        <span class="nav-item">Manage Job Listings</span>
                        </a>
                        </li>
                       
                        <li>
                            <a href="Notifications.php">
                            <span class="icon"><ion-icon name="notifications-outline"></ion-icon> </span>
                        <span class="nav-item">Notifications</span>
                        </a>
                        </li>
                        <li class="active1">
                            <a href="Feedback.php" class="active2"><span class="icon"><ion-icon name="chatbubble-outline"></ion-icon></span>
                        <span class="nav-item">Feedbacks</span>
                        </a>
                        </li>
                        <li>
                            <a href="ReportGeneration.php"><span class="icon"><ion-icon name="document-text-outline"></ion-icon></span>
                        <span class="nav-item">Report Generation</span>
                        </a>
                        </li>
                    
                    
                        <li>
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
                            <h1>Feedbacks</h1>
                        </div>
                        <!--userImg-->  
                        <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?> 
                    </div>
                    <div class="details">
                        <div class="Feedback">
                            <div class="FeedbackHeader">
                                <h2><ion-icon name="chatbubble-outline"></ion-icon> Feedbacks</h2>
                            <div class="textheader"><div class="text">There Are <a href="#"><?php  echo $totalFeedbacks;?></a> Feedbacks</div>
                            <div class="all"><a href="#" id="deleteLink">Clear All</a></div>
                        </div>
                        <div class="cardbox">
                            <?php
                            if ($result) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $feedbackId = $row['feedback_id'];
                                    $feedbackContent = $row['feedbacktext'];
                                    $timestamp = $row['timestamp'];
                                    $jfirstName = $row['jfirst_name'];
                                    $jlastName = $row['jlast_name'];
                                    $employerName = $row['employer_name'];
                                    $jobseekerId = $row['jobseeker_id'];
                                    $employerId = $row['employer_id'];

                           // Determine who submitted the feedback and generate HTML accordingly
                                if (!empty($jobseekerId)) {
                                    $feedbackBy = "Feedback Submitted by Job Seeker: " . $jfirstName . ' ' . $jlastName;
                                } elseif (!empty($employerId)) {
                                    $feedbackBy = "Feedback Submitted By Employer: $employerName";
                                } else {
                                    // Handle other cases or unknown users
                                    $feedbackBy = "Feedback Submitted by an Unknown User";
                                }
                            ?>
                            <div class="card">
                                    <div class="message"><a href="#"><?php  echo $feedbackBy;?></a></div>
                                    <div class="View"><a href="ViewFeedback.php?feedback_id=<?php echo $feedbackId; ?>">View Feedback</a></div>
                                    <?php  echo $timestamp;?>      
                        </div>
                        <?php
                         }
                        } else {
                            echo "No feedback found.";
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
            if (window.location.search.indexOf('feedbackdeleted') > -1) {
                Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Feedback Deleted Successfully',
                showConfirmButton: false,
                timer: 1500
            });
            setTimeout(function () {
                window.location.href = 'Feedback.php'; // Redirect to the final target page
            }, 1600); // Adjust the delay time as needed
        }

        
//clearAll
document.addEventListener("DOMContentLoaded", function () {
    var deleteLink = document.getElementById("deleteLink");

    deleteLink.addEventListener("click", function (e) {
        e.preventDefault(); // Prevent the default link behavior

        Swal.fire({
            title: 'Are You Sure You Want To Clear All Feedbacks?',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Yes, Clear it',
            cancelButtonText: 'No, keep it',
        }).then((result) => {
            if (result.isConfirmed) {
                // User confirmed, proceed with deletion
                var form = document.createElement("form");
                form.method = "POST";
                form.action = "ClearFeedbacks.php";

                // Append the form to the document body
                document.body.appendChild(form);

                form.submit();
            }
        });
    });
});

   

//sweetalert    
    if (window.location.search.indexOf('feedbackscleared') > -1) {
                Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Feedbacks Cleared Successfully',
                showConfirmButton: false,
                timer: 1500
            });
            setTimeout(function () {
                window.location.href = 'Feedback.php'; // Redirect to the final target page
            }, 1600); // Adjust the delay time as needed
        }
            </script>
        </body>
        </html>