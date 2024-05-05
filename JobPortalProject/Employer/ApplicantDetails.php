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
                      // Retrieve the first name and last name from URL parameters
                            $userId = $_GET['user_id'];
                            $applicationId = $_GET['application_id'];
                           

                            // Query the database for the specific job seeker
                            $userId = mysqli_real_escape_string($conn, $userId);
                            

                            $query = "SELECT * FROM `jobseekerprofiles` WHERE `user_id` = '$userId'";
                            $result = $conn->query($query);

                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                            
                                // Retrieve job seeker details
                                $firstname = $row['first_name'];
                                $lastname = $row['last_name'];
                                $contact = $row['contact_number'];
                                $location = $row['location'];
                                $skills = $row['skills'];
                                $email1 = $row['email'];
                                $experience = $row['experience'];
                                $industry = $row['industry_preference'];
                                $jobtype= $row['job_type_preference'];
                                $jobrole = $row['job_role_preference'];
                                $salary = $row['salary_expectation'];
                                $languages = $row['languages'];
                                $availability = $row['availability'];
                                $resume = $row['resume'];
                                $profilePictureURL2 = $row['profile_picture'];
                           
                            



                




?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Applicants</title>
        <link rel="stylesheet" type="text/css" href="css/SeekerDetails.css">
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
                            <a href="Applicants.php"  class="active2">
                            <span class="icon"><ion-icon name="document-text-outline"></ion-icon> </span>
                        <span class="nav-item">Applicants</span>
                        </a>
                        </li>
                        <li class="menu">
                            <a href="SelectedCandidates.php" >
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
                            <h1>Applicant Details</h1>
                        </div>
                        <!--userImg-->  
                        <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?>
                    </div>
                    <div class="details">
                        <div class="Profile">
                            <div class="ProfileHeader">
                                <ion-icon name="person-outline"></ion-icon> <h2>APPLICANT PROFILE</h2>
                                <?php echo '<div class="user"><img src="' . $profilePictureURL2 . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?>
                            </div>
                            
                            <table>
                            <tr>
                                        <th>Name</th>
                                        <td style="position:relative; top:1px;"><?php echo $firstname; echo" "; echo $lastname; ?> </td>
                                </tr>
                                <tr>
                                        <th>Email</th>
                                        <td><?php echo $email1; ?></td>
                                </tr>
                                <tr>
                                        <th>Contact Number</th>
                                        <td><?php echo $contact; ?></td>
                                </tr>
                                </tr> <tr>
                                    <th>Location</th>
                                    <td><?php echo $location; ?></td>
                                </tr>
                                <tr>
                                <th>Skills</th>
                                    <td><textarea readonly><?php echo $skills; ?>
                                    </textarea></td>
                                </tr>
                                <tr>
                                    <th>Experience</th>
                                    <td><?php echo $experience; ?></td>
                                </tr>
                               
                                <tr>
                                    <th>Industry Preference</th>
                                    <td><?php echo $industry; ?></td>
                                </tr>
                                <tr>
                                    <th>Job Type Preference</th>
                                    <td><?php echo $jobtype; ?></td>
                                </tr>
                                <tr>
                                    <th>Job Role Preference</th>
                                    <td><?php echo $jobrole; ?></td>
                                </tr>
                                <tr>
                                    <th>Salary Expectation</th>
                                    <td><?php echo $salary; ?></td>
                                </tr>
                                <tr>
                                    <th>Languages</th>
                                    <td><?php echo $languages; ?></td>
                                </tr>
                                <tr>
                                    <th>Availability</th>
                                    <td><?php echo $availability; ?></td>
                                </tr>
                                <tr>
                                <th>Resume</th>
                                    <td><a href="ApplicantResume.php?user_id=<?php echo $userId; ?>" id="resumeanchor">View Resume</a></td>
                                </tr>
                         
                            </table>
                            <?php
                        } else {
                                echo 'Job seeker not found.';
                            }
                        ?>
                            <div class="btns">
                            <div class="select"><a href="#" id="selectLink" >Select <ion-icon name="checkmark-outline"></ion-icon></a></div>&nbsp &nbsp
                            <div class="select"><a href="#" id="rejectLink" >Reject <ion-icon name="close-circle-outline" style="position: relative; top: 2px; font-size: 1em;"></ion-icon></a></div>&nbsp &nbsp

                            
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
         
//select
    document.addEventListener("DOMContentLoaded", function () {
        var selectLink = document.getElementById("selectLink");

        selectLink.addEventListener("click", function (e) {
            e.preventDefault(); // Prevent the default link behavior

            Swal.fire({
                title: 'Are You Sure You Want Select This Applicant?',
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    // User confirmed, proceed with deletion
                    var form = document.createElement("form");
                    form.method = "POST";
                    form.action = "SelectApplicant.php";

                    var applicationInput = document.createElement("input");
                    applicationInput.type = "hidden";
                    applicationInput.name = "application_id";
                    applicationInput.value = "<?php echo $applicationId; ?>";

                 

                    form.appendChild(applicationInput);
                 

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });


//reject
document.addEventListener("DOMContentLoaded", function () {
        var RejectLink = document.getElementById("rejectLink");

        RejectLink.addEventListener("click", function (e) {
            e.preventDefault(); // Prevent the default link behavior

            Swal.fire({
                title: 'Are You Sure You Want Reject This Applicant?',
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    // User confirmed, proceed with deletion
                    var form = document.createElement("form");
                    form.method = "POST";
                    form.action = "RejectApplicant.php";

                    var applicationInput = document.createElement("input");
                    applicationInput.type = "hidden";
                    applicationInput.name = "application_id";
                    applicationInput.value = "<?php echo $applicationId; ?>";

                 

                    form.appendChild(applicationInput);
                 

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });


            </script>
        </body>
</html>