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

             // Query to count the total users in jobseekerprofiles
             $countQuery = "SELECT COUNT(*) AS totalUsers FROM jobseekerprofiles";
             $result = mysqli_query($conn, $countQuery);

             if ($result) {
                 $row = mysqli_fetch_assoc($result);
                 $totalUsers = $row['totalUsers'];
             }
            
     
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Job Seeker Management</title>
        <link rel="stylesheet" type="text/css" href="css/Jobseekermanage.css">
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
                        <li class="active1">
                            <a href="Jobseekermanage.php" class="active2">
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
                        <li>
                            <a href="Feedback.php"><span class="icon"><ion-icon name="chatbubble-outline"></ion-icon></span>
                        <span class="nav-item">Feedbacks</span>
                        </a>
                        </li>
                        <li>
                            <a href="ReportGeneration.php"><span class="icon"><ion-icon name="document-text-outline"></ion-icon></span>
                        <span class="nav-item">Report Generation</span>
                        </a>
                    </li>


                        <li>
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
                            <h1>Job Seeker Management</h1>
                        </div>
                        <!--userImg-->  
                        <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?> 
                    </div>
                    <div class="details">
                        <div class="CandidateListings">
                            <div class="ListingsHeader">
                                <h2>Job Seekers Listing</h2>
                             <div class="Search">
                             <form method="post">
                                <label>
                                   
                            <input type="search" name="searchField" id="searchField" placeholder="Search Here">
                            <ion-icon name="search-outline"></ion-icon>
                            <div class="searchbtn"><a href="javascript:void(0);" id="searchIconLink" onclick="document.forms[0].submit();"><ion-icon name="search-outline"></ion-icon></a></div>
                            <div class="dropdown">
                            <input type="text" class="text02" readonly placeholder="Industry" id="industry" name="industry">
                            <div class="option">
                                <div onmouseover="show('Software Dev')">Software Dev</div>
                                <div onmouseover="show('Telecommunications')">Telecommunications</div>
                                <div onmouseover="show('Finance')">Finance</div>
                            </div>
                            </div>
                            <div class="dropdown1">
                            <input type="text" class="text03" readonly placeholder="Job Type" id="jobtype" name="jobtype">
                            <div class="option1">
                                <div onmouseover="show1('Part-Time')">Part-Time</div>
                                <div onmouseover="show1('Full-Time')">Full-Time</div>
                                <div onmouseover="show1('Internship')">Internship</div>
                            </div>
                            </div> 
                         
                            </label>
                             </form>
                            <div class="textheader"><div class="text">There Are <a href="#"><?php  echo $totalUsers;?></a> Job Seekers</div>            
                        <div class="all"><a href="#">See All</a></div>   
                        </div>
                        </div>
                        <div class="cardbox">
                        <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Search button is clicked

                        $searchValue = $_POST['searchField'];
                        $industryValue = $_POST['industry'];
                        $jobTypeValue = $_POST['jobtype'];

                        // Check if at least one filter is set
                        if (empty($searchValue) && empty($industryValue) && empty($jobTypeValue)) {
                            echo '<script>alert("Enter a Search Item or Filter");</script>';
                            echo '<script>window.location.href = "Jobseekermanage.php";</script>';
                        } else {
                            // Check if a search value is provided
                            $queryConditions = [];

                            if (!empty($searchValue)) {
                                $searchValue = mysqli_real_escape_string($conn, $searchValue);

                                $queryConditions[] = "(`first_name` LIKE '%$searchValue%'
                                    OR `last_name` LIKE '%$searchValue%'
                                    OR `skills` LIKE '%$searchValue%'
                                    OR `availability` LIKE '%$searchValue')";
                            }

                            // Check if industry filter is provided
                            if (!empty($industryValue)) {
                                $industryValue = mysqli_real_escape_string($conn, $industryValue);
                                $queryConditions[] = "(`industry_preference` = '$industryValue')";
                            }

                            // Check if job type filter is provided
                            if (!empty($jobTypeValue)) {
                                $jobTypeValue = mysqli_real_escape_string($conn, $jobTypeValue);
                                $queryConditions[] = "(`job_type_preference` = '$jobTypeValue')";
                            }

                            // Build the final query
                            $query = "SELECT `user_id`, `first_name`, `last_name`, `experience`, `skills`, `availability`, `profile_picture`
                                    FROM `jobseekerprofiles`
                                    WHERE " . implode(' AND ', $queryConditions);

                            $result = mysqli_query($conn, $query);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $user_id = $row['user_id'];
                                    $firstname = $row["first_name"];
                                    $lastname = $row["last_name"];
                                    $experience = $row["experience"];
                                    $skills = $row["skills"];
                                    $availability = $row["availability"];
                                    $profilePictureURL1 = $row['profile_picture'];
                                    
                                    // Output the retrieved data and replace the default code below
                        
                                    ?>
                                     <?php
                        echo '<a href="SeekerDetails.php?user_id='.$user_id.'" " style="text-decoration: none; color: black;">';
                        ?>
                                    <div class="card">
                                        <div class="profpic">
                                            <?php
                                           
                                            echo '<img src="'. $profilePictureURL1 . '" onerror="this.src=\'../images/user.png\';">';
                                            ?>
                                        </div>
                                        <div class="Candname">
                                            <h4><?php echo $firstname . " " . $lastname; ?></h4>
                                        </div>
                                        <div class="Candexp">Experience : <?php echo $experience; ?></div>
                                        <div class="Candskills">
                                            <h4>View Skills</h4>
                                            <div class="Skillbox">
                                                <div><?php echo $skills; ?></div>
                                            </div>
                                            <span><?php echo $availability; ?></span>
                                        </div>
                                    </div>
                                    <?php
                                    echo '</a>';
                                }
                            } else {
                                echo "No results found.";
                            }
                        }
                    } else {
                        // Default view
                        $sql = "SELECT `user_id`,`first_name`, `last_name`, `experience`, `skills`, `availability`, `profile_picture` FROM jobseekerprofiles ORDER BY joineddate DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                        $user_id = $row['user_id'];
                        $firstname = $row["first_name"];
                        $lastname = $row["last_name"];
                        $experience = $row["experience"];
                        $skills = $row["skills"];
                        $availability = $row["availability"];
                        $profilePictureURL1 = $row['profile_picture'];
                        ?>
                        <?php
                        echo '<a href="SeekerDetails.php?user_id='.$user_id.'" " style="text-decoration: none; color: black;">';
                        ?>
                            <div class="card">
                                <div class="profpic">
                                 
                              <?php
                              
                               echo '<img src="'. $profilePictureURL1 . '" onerror="this.src=\'../images/user.png\';">';?> 
                                
    
                                </div>
                                <div class="Candname">
                                    <h4><?php echo $firstname;echo" "; echo $lastname ;?></h4></div>
                                    <div class="Candexp">Experience : <?php echo $experience;?></div> 
                               
                                <div class="Candskills">
                                    <h4>View Skills</h4>
                                    <div class="Skillbox">
                                        <div><?php echo $skills; ?>
                                        </div> 
                                    </div>
                                    <span><?php echo $availability; ?></span>
                                </div>
                            </div>
                            <?php
                            echo '</a>';
                            }
                        } else {
                            echo "No job seekers found.";
                        }
                    }
                    
                
                    ?>

                            
                        
                         
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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="js/logout.js"></script>
        <script src="js/highlightnavigation.js"></script>
        <script>
            //dropdown
            let dropdown = document.querySelector('.dropdown');
            dropdown.onclick = function(){
                dropdown.classList.toggle('active');
            }
            //dropdown options hover
            function show(a)
            {
                document.querySelector('.text02').value = a;
            }
             //dropdown1
             let dropdown1 = document.querySelector('.dropdown1');
            dropdown1.onclick = function(){
                dropdown1.classList.toggle('active');
            }
            //dropdown1 options hover
            function show1(b)
            {
                document.querySelector('.text03').value = b;
            }


            //MenuToggle
            let toggle = document.querySelector('.toggle');
            let navigation = document.querySelector('.navigation');
            let main = document.querySelector('.main');

            toggle.onclick = function()
            {
                navigation.classList.toggle('active');
                main.classList.toggle('active');
            }
            
            //seeall
            const viewAllButton = document.querySelector('.all ');
             const appliedJobs = document.querySelector('.CandidateListings');
             viewAllButton.addEventListener('click', function(event) {
             event.preventDefault(); 
             appliedJobs.classList.toggle('active');
            });
            
        
        
//sweetalert    
            if (window.location.search.indexOf('accountdeleted') > -1) {
                Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Account Deleted Successfully',
                showConfirmButton: false,
                timer: 1500
            });
            setTimeout(function () {
                window.location.href = 'jobseekermanage.php'; // Redirect to the final target page
            }, 1600); // Adjust the delay time as needed
        }
        </script>
        </body>
        </html>