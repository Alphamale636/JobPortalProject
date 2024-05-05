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

            if (isset($_SESSION['username'])) {
            $seekerUsername = $_SESSION['username'];
            $query = "SELECT * FROM jobseekerprofiles WHERE username = '$seekerUsername'";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $jobseekerId = $row['user_id'];
                }
            }


}



                    $applicationId = $_GET['application_id'];
              

                    // Get the job_id and status associated with the application
                    $query = "SELECT job_id, `status` FROM applications WHERE application_id = ?";
                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, "i", $applicationId);
                    mysqli_stmt_execute($stmt);
                   
                    mysqli_stmt_bind_result($stmt, $jobId, $status);
                    mysqli_stmt_fetch($stmt);
                    mysqli_stmt_close($stmt);

                    
                    if ($status == "0") {
                        $status = "Pending";
                    } else if ($status == "1") {
                        $status = "Selected";
                    } else if ($status == "2") {
                        $status = "Rejected";
                    }

                    // Use a prepared statement to retrieve job details
                    $query1 = "SELECT jl.jobtitle, ep.employer_id, ep.company_name, jl.jobtype, jl.experience, jl.jobdescription, jl.joblevel, jl.salary, jl.posteddate, ep.logo, ep.location
                    FROM joblistings jl
                    INNER JOIN employerprofiles ep ON jl.employer_id = ep.employer_id
                    WHERE jl.job_id = ?";
                    
                    $stmt1 = mysqli_prepare($conn, $query1);
                    mysqli_stmt_bind_param($stmt1, "i", $jobId);
                    mysqli_stmt_execute($stmt1);
                    mysqli_stmt_store_result($stmt1); // Store the result
                    mysqli_stmt_bind_result($stmt1, $jobtitle, $employerId, $companyname, $jobtype, $experience, $jobdescription, $joblevel, $salary, $postedDate, $logo, $location);
                    
                

                    if (mysqli_stmt_fetch($stmt1)) {
               




?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Job Seeker Manage</title>
        <link rel="stylesheet" type="text/css" href="css/JobDetails.css">
        <link rel="stylesheet" type="text/css" href="css/seekerdash.css">
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
                                <a href="DashboardSection.php"> 
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
                    
                    
                
                <div class="main">
                    <div class="topbar">
                        <div class="toggle">
                            <ion-icon name="menu-outline"></ion-icon>
                        </div>
                        <div class="topname">
                            <h1>APPLICATIONS</h1>
                        </div>
                        <!--userImg-->  
                        <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?>
                    </div>
                    <div class="details">
                        <div class="Profile">
                            <div class="ProfileHeader">
                                <ion-icon name="person-outline"></ion-icon> <h2>APPLICATION DETAILS</h2>
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
                                <tr>
                                <th>Employer</th>
                                <td><a href="ViewEmployer2.php?employer_id=<?php echo $employerId; ?>" id="empdetails">View Details</a></td> 
                                </td>
                             </tr>
                             <tr>
                                <th>Status</th>
                                    <td style="font-weight: 700;"><?php echo $status; ?></td>
                                    </td>
                                </tr>
                               
                               
                            </table>
                            <?php
                    }
                        else {
                                echo 'application not found.';
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
                title: 'Are You Sure You Want To Delete This Application?',
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Yes, Delete it!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    // User confirmed, proceed with deletion
                    var form = document.createElement("form");
                    form.method = "POST";
                    form.action = "DeleteApplication.php";

                    var firstnameInput = document.createElement("input");
                    firstnameInput.type = "hidden";
                    firstnameInput.name = "application_id";
                    firstnameInput.value = "<?php echo $applicationId; ?>";

          


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