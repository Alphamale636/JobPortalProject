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




        
        
        


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobtitle = $_POST['jobtitle'];
    $jobtype = $_POST['jobtype'];
    $jobdescription = $_POST['jobdescription'];
    $joblevel = $_POST['joblevel'];
    $salary = $_POST['salary'];
    $experience= $_POST['experience'];
    $posteddate = $_POST["posteddate"];
 
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];

         // Query the database to get the employer_id
    $query = "SELECT employer_id FROM employerprofiles WHERE username = ?";

    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $employerId);
        
        if (mysqli_stmt_fetch($stmt)) {
            // Store the employer_id in a session variable
            $_SESSION['employer_id'] = $employerId;
        }

        mysqli_stmt_close($stmt);
    }
}
    

    // Insert the data into the database
    $query = "INSERT INTO joblistings (`jobtitle`,  `jobtype`, `jobdescription`, `joblevel`, `salary`, `experience`, `posteddate`, `employer_id`) VALUES (?, ?, ?, ?, ?,  ?, ?, ?)";

    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "ssssssss",$jobtitle,  $jobtype, $jobdescription, $joblevel, $salary, $experience, $posteddate, $employerId);

        if (mysqli_stmt_execute($stmt)) {
            // Job Posted
            
             // Send notifications here
            // Identify admin users by querying the adminprofiles table
            $queryAdmins = "SELECT admin_id FROM adminprofiles";
            $resultAdmins = mysqli_query($conn, $queryAdmins);

            // Fetch the employer's name using the session username
            if (isset($_SESSION['username'])) {
                $employerUsername = $_SESSION['username'];

                $queryEmployerName = "SELECT company_name FROM employerprofiles WHERE username = ?";
                $stmtEmployerName = mysqli_prepare($conn, $queryEmployerName);
                mysqli_stmt_bind_param($stmtEmployerName, "s", $employerUsername);
                mysqli_stmt_execute($stmtEmployerName);
                mysqli_stmt_bind_result($stmtEmployerName, $employerName);
                mysqli_stmt_fetch($stmtEmployerName);
                mysqli_stmt_close($stmtEmployerName);

                // Insert notifications for each admin user with the employer's name
                while ($admin = mysqli_fetch_assoc($resultAdmins)) {
                    $adminUserId = $admin['admin_id'];
                    $notificationMessage = "New job posted by $employerName: '$jobtitle'";

                    $insertQuery = "INSERT INTO notifications (`user_id`, `message`) VALUES (?, ?)";
                    $stmt = mysqli_prepare($conn, $insertQuery);
                    mysqli_stmt_bind_param($stmt, "is", $adminUserId, $notificationMessage);
                    mysqli_stmt_execute($stmt);
                }
            }
            
            header('Location: PostJobs.php?jobPosted=1');
            
            exit();
            
            
        } else {
            // Failed to Post Job
            $error = "Error: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        $error = "Database query error. Please try again later.";
    }


            

    
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Post Jobs</title>
        <link rel="stylesheet" type="text/css" href="css/Postjobs.css">
        <link rel="stylesheet" type="text/css" href="css/empdash.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                        <li class="active1">
                            <a href="PostJobs.php" class="active2">
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
                            <span class="icon"> <ion-icon name="list-outline"></ion-icon></span>
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
                    
                
                <div class="main">
                    <div class="topbar">
                        <div class="toggle">
                            <ion-icon name="menu-outline"></ion-icon>
                        </div>
                        <div class="topname">
                            <h1>POST JOBS</h1>
                        </div>
                        <!--userImg-->  
                        <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?>
                    </div>
                    <div class="details">
                        <div class="Postjobs">
                            <div class="PostjobsHeader">
                                <ion-icon name="person-outline"></ion-icon> <h2>POST A JOB</h2>
                                <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?>
                            </div>
                            <form method="post" action="PostJobs.php" id="myForm">
                        <table>
                              <tr>
                                <th>Job Title</th>
                                <td><input type="text" placeholder="Type Here.." name="jobtitle"></td>
                             </tr>
                             
                            <tr>
                                <th>Job Description</th>
                                <td><textarea placeholder="Type Here.." name="jobdescription"></textarea></td>
                            </tr>
                            <tr><th>Job Type</th>
                                <td><select name="jobtype">
                                    <option value="" disabled selected>Select an option</option>
                                    <option value="Part-Time">Part-Time</option>
                                    <option value="Full-Time">Full-Time</option>
                                    <option value="Internship">Internship</option>
                                  </select>
                                </td>
                                  </tr>
                                  <tr>
                                <th>Job Level</th>
                                <td><input type="text" placeholder="Type Here.." name="joblevel"></td>
                            </tr>
                                  <tr>
                                    <th>Salary Range</th>
                                    <td><input type="text" placeholder="eg : $100-$1000" name="salary"></td>
                                </tr>
                                <tr>
                                    <th>Experience Level</th>
                                    <td><select name="experience">
                                        <option value="" disabled selected>Select an option</option>
                                        <option value="0 years">0 year</option>
                                        <option value="1 year">1 year</option>
                                        <option value="2 years">2 years</option>
                                        <option value="3 years">3 years</option>
                                        <option value="3+ years">3+ years</option>
                                      </select>
                                      <input type="hidden" name="posteddate" id="posteddate">
                                    </td>
                                </tr>
                                
                        </table>
                        <div class="btns">
                            <div class="post"><a href="#" id="submitForm"><ion-icon name="paper-plane-outline"></ion-icon>&nbsp;POST </a></div>
                        </div>
                        </form>
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

//Validate

            document.getElementById("submitForm").addEventListener("click", function(event) {
            event.preventDefault(); // Prevent the default link behavior (navigating)

            // Get the form elements by their names and check their values
            const jobtitle = document.querySelector('input[name="jobtitle"]').value.trim();
            const jobdescription = document.querySelector('textarea[name="jobdescription"]').value.trim();
            const jobtype = document.querySelector('select[name="jobtype"]').value.trim();
            const joblevel = document.querySelector('input[name="joblevel"]').value.trim();
            const experience = document.querySelector('select[name="experience"]').value.trim();
            const salary = document.querySelector('input[name="salary"]').value.trim();

            // Check if any of the required fields are empty
            if (!jobtitle || !jobdescription || !jobtype || !joblevel || !experience || !salary) {
                Swal.fire('Please Fill Out All The Fields!')
            } else {
                // If validation passes, submit the form
                const currentDate = new Date().toISOString().split("T")[0]; // Format: yyyy-mm-dd
                document.getElementById("posteddate").value = currentDate;
                document.getElementById("myForm").submit();
            }
            });

      

if (window.location.search.indexOf('jobPosted') > -1) {
        Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Job Posted Successfully',
        showConfirmButton: false,
        timer: 1500
    });
    setTimeout(function () {
        window.location.href = 'PostJobs.php'; // Redirect to the final target page
    }, 1600); // Adjust the delay time as needed
}
  
            </script>
        </body>
</html>