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



                            // Retrieve the id from URL parameters
                            $jobId = $_GET['job_id'];
                            
                            // Query the database for the specific job
                            $jobId = mysqli_real_escape_string($conn, $jobId);
                            

                            $query = "SELECT jl.job_id, jl.jobtitle, ep.employer_id, ep.company_name, jl.jobtype, jl.experience, jl.jobdescription, jl.joblevel, jl.salary, jl.posteddate, ep.logo, ep.location
                            FROM joblistings jl
                            INNER JOIN employerprofiles ep ON jl.employer_id = ep.employer_id
                            WHERE jl.job_id = '$jobId'";

                           
                              


                            $result = mysqli_query($conn, $query);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $employerId = $row['employer_id'];
                                    $jobId = $row['job_id'];
                                    $jobtitle = $row['jobtitle'];
                                    $companyname = $row['company_name'];
                                    $jobtype = $row['jobtype'];
                                    $joblevel = $row['joblevel'];
                                    $salary = $row['salary'];
                                    $experience = $row['experience'];
                                    $location = $row['location'];
                                    $jobdescription = $row['jobdescription'];
                                    $logo = $row['logo'];
                                   
                                    $postedDate = $row['posteddate']; 

                                    $postedDateTime = new DateTime($postedDate);

                                        // Get the current date and time
                                        $currentDateTime = new DateTime();

                                        // Calculate the difference between the current date and the posted date
                                        $dateInterval = $currentDateTime->diff($postedDateTime);

                                        // Format the result as "X days ago"
                                        if ($dateInterval->d == 0) {
                                            $postedAgo = 'Today';
                                        } elseif ($dateInterval->d == 1) {
                                            $postedAgo = 'Yesterday';
                                        } else {
                                            $postedAgo = $dateInterval->d . ' days ago';
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


                




?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Job Details</title>
        <link rel="stylesheet" type="text/css" href="css/PostedDetails.css">
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
                        <div class="Profile">
                            <div class="ProfileHeader">
                                <ion-icon name="person-outline"></ion-icon> <h2>JOB DETAILS</h2>
                                <?php echo '<div class="user"><img src="' . $logo . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?>
                            </div>
                            
                            <table>
                            <tr>
                                        <th>Job Title</th>
                                        <td style="position:relative; top:1px;"><?php echo $jobtitle; ?> </td>
                                </tr>
                                <tr>
                                <th>Job Description</th>
                                    <td><textarea readonly><?php echo $jobdescription; ?>
                                    </textarea></td>
                                </tr>
                                <tr>
                                        <th>Job Type</th>
                                        <td><?php echo $jobtype; ?></td>
                                </tr>
                                </tr> <tr>
                                    <th>Location</th>
                                    <td><?php echo $location; ?></td>
                                </tr>
                                <tr>
                                <th>Job Level</th>
                                    <td><?php echo $joblevel; ?></td>
                                   
                                    </td>
                                </tr>
                               
                               
                                <tr>
                                <th>Salary Range</th>
                                    <td><?php echo $salary; ?></td>
                                    
                                    </td>
                                </tr>
                                
                                <tr>
                                <th>Experience</th>
                                    <td><?php echo $experience; ?></td>
                                    
                                    </td>
                                </tr>
                               
                               
                               
                            </table>
                            <?php
                        } else {
                                echo 'Job not found.';
                            }
                        ?>
                            <div class="btns">
                            <div class="delete"><a href="#" id="deleteLink">Delete <ion-icon name="trash-outline"></ion-icon></a></div>&nbsp &nbsp

                            
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
          
    document.addEventListener("DOMContentLoaded", function () {
        var deleteLink = document.getElementById("deleteLink");

        deleteLink.addEventListener("click", function (e) {
            e.preventDefault(); // Prevent the default link behavior

            Swal.fire({
                title: 'Are You Sure You Want To Delete This Job?',
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'No, keep it',
            }).then((result) => {
                if (result.isConfirmed) {
                    // User confirmed, proceed with deletion
                    var form = document.createElement("form");
                    form.method = "POST";
                    form.action = "DeleteJob.php";

                    var firstnameInput = document.createElement("input");
                    firstnameInput.type = "hidden";
                    firstnameInput.name = "job_id";
                    firstnameInput.value = "<?php echo $jobId; ?>";

                    form.appendChild(firstnameInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });



            </script>
        </body>
</html>