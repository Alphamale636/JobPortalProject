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
             $countQuery = "SELECT COUNT(*) AS totalUsers FROM employerprofiles WHERE employer_id <> 1";
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
        <title>Employer Management</title>
        <link rel="stylesheet" type="text/css" href="css/EmployerManage.css">
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
                        <li>
                            <a href="Jobseekermanage.php">
                            <span class="icon"> <ion-icon name="people-outline"></ion-icon> </i></span>
                        <span class="nav-item">Manage Job Seekers</span>
                        </a>
                        </li>
                        <li class="active1">
                            <a href="EmployerManage.php" class="active2">
                            <span class="icon"><ion-icon name="people"></ion-icon> </span>
                        <span class="nav-item">Manage Employers</span>
                        </a>
                        </li>
                        <li class="menu">
                            <a href="JobListingsManage.php">
                            <span class="icon"><ion-icon name="list-outline"></ion-icon> </span>
                        <span class="nav-item">Manage Job Listings</span>
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
                        <span class="nav-item">Feedbacks</span>
                        </a>
                        </li>
                        <li class="menu">
                            <a href="ReportGeneration.php"><span class="icon"><ion-icon name="document-text-outline"></ion-icon></span>
                        <span class="nav-item">Report Generation</span>
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
                            <h1>Employer Management</h1>
                        </div>
                        <!--userImg-->  
                        <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?> 
                    </div>
                    <div class="details">
                        <div class="EmployerListings">
                            <div class="ListingsHeader">
                                <h2>Employer Listings</h2>
                             <div class="Search">
                             <form method="post">
                                <label>
                                <input type="search" name="searchField" id="searchField" placeholder="Search Here">
                            <ion-icon name="search-outline"></ion-icon>
                            <div class="searchbtn"><a href="javascript:void(0);" id="searchIconLink" onclick="document.forms[0].submit();"><ion-icon name="search-outline"></ion-icon></a></div>
                            <div class="dropdown1">
                            <input type="text" class="text03" readonly placeholder="Industry" id="industry" name="industry">
                            <div class="option1">
                                <div onmouseover="show('Software Dev')">Software Dev</div>
                                <div onmouseover="show('Telecommunications')">Telecommunications</div>
                                <div onmouseover="show('Finance')">Finance</div>
                            </div>
                            </div>
                       
                         
                            </label>
                             </form>
                            <div class="textheader"><div class="text">There Are <a href="#"><?php echo $totalUsers ?></a> Employers</div>            
                        <div class="all"><a href="#">See All</a></div>   
                        </div>
                        </div>
                        <div class="cardbox">
            <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Search button is clicked

                        $searchValue = $_POST['searchField'];
                        $industryValue = $_POST['industry'];
                       

                        // Check if at least one filter is set
                        if (empty($searchValue) && empty($industryValue)) {
                            echo '<script>alert("Enter a Search Item or Filter");</script>';
                            echo '<script>window.location.href = "EmployerManage.php";</script>';
                        } else {
                            // Check if a search value is provided
                            $queryConditions = [];

                            if (!empty($searchValue)) {
                                $searchValue = mysqli_real_escape_string($conn, $searchValue);

                                $queryConditions[] = "(`company_name` LIKE '%$searchValue%'
                                    OR `about_us` LIKE '%$searchValue%')";
                            }

                            // Check if industry filter is provided
                                                    if (!empty($industryValue)) {
                                $industryValue = mysqli_real_escape_string($conn, $industryValue);
                                $queryConditions[] = "(`industry` = '$industryValue')";
                            }

                           

                          
                                    // Build the final query
                                    $query = "SELECT e.employer_id, e.company_name, e.location, e.logo, COUNT(j.job_id) AS posted_jobs
                                    FROM employerprofiles e
                                    LEFT JOIN joblistings j ON e.employer_id = j.employer_id";
                                     $queryConditions[] = "e.employer_id <> 1";

                                if (!empty($queryConditions)) {
                                    $query .= " WHERE " . implode(' AND ', array_map(function ($condition) {
                                        return "($condition)";
                                    }, $queryConditions));
                                }

                                $query .= " GROUP BY e.employer_id";
                                                
                                $result = mysqli_query($conn, $query);
                                
                                if (mysqli_num_rows($result) > 0)  {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $employerId = $row['employer_id'];
                                        $companyName = $row['company_name'];
                                        $location = $row['location'];
                                        $logo = $row['logo'];
                                        $postedJobs = $row['posted_jobs'];
                                    
                         
                                    ?>
                                     <?php
                        echo '<a href="EmployerDetails.php?employer_id=' . $employerId. '" style="text-decoration: none; color: black;">';
                        ?>
                                    <div class="card">
                                        <div class="company">
                                            <?php
                                             $parentDirectory = '../'; // Go back one directory
                                             $logo = $parentDirectory . $logo;
                                            echo '<img src="'. $logo . '" onerror="this.src=\'../images/user.png\';">';
                                            ?>
                                        </div>
                                        <div class="Empname">
                                            <h4><?php echo $companyName ?></h4>
                                        </div>
                                        <div class="location">Location : <?php echo $location; ?></div>
                                        <div class="postedjobs">
                                            <h4>Posted Jobs</h4>
                                           
                                            <span class="number"><u><?php echo $postedJobs; ?></u></span>
                                        </div>
                                    </div>
                                    <?php
                                    echo '</a>';
                                }
                            }
                             else {
                                echo "No results found.";
                            }
                        
                        }
                    } else {
                        // Default view
                        $query = "SELECT e.employer_id, e.company_name, e.location, e.logo, COUNT(j.job_id) AS posted_jobs
                            FROM employerprofiles e
                            LEFT JOIN joblistings j ON e.employer_id = j.employer_id WHERE e.employer_id <>1
                            GROUP BY e.employer_id";
                 
                                $result = mysqli_query($conn, $query);
                                
                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $employerId = $row['employer_id'];
                                        $companyName = $row['company_name'];
                                        $location = $row['location'];
                                        $logo = $row['logo'];
                                        $postedJobs = $row['posted_jobs'];
                                    
                         
                                    ?>
                                 <?php
                                    echo '<a href="EmployerDetails.php?employer_id=' . $employerId. '" style="text-decoration: none; color: black;">';
                                ?>

                                    <div class="card">
                                        <div class="company">
                                            <?php
                                            
                                            echo '<img src="'. $logo . '" onerror="this.src=\'../images/user.png\';">';
                                            ?>
                                        </div>
                                        <div class="Empname">
                                            <h4><?php echo $companyName ?></h4>
                                        </div>
                                        <div class="location">Location : <?php echo $location; ?></div>
                                        <div class="postedjobs">
                                            <h4>Jobs Posted</h4>
                                           
                                            <span class="number"><u><?php echo $postedJobs; ?></u></span>
                                        </div>
                                    </div>
                                    <?php
                                    echo '</a>';
                                }
                            } else {
                                echo "No results found.";
                            }
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
         
             //dropdown1
             let dropdown1 = document.querySelector('.dropdown1');
            dropdown1.onclick = function(){
                dropdown1.classList.toggle('active');
            }
            //dropdown1 options hover
            function show(b)
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
             const appliedJobs = document.querySelector('.EmployerListings');
             viewAllButton.addEventListener('click', function(event) {
             event.preventDefault(); 
             appliedJobs.classList.toggle('active');
            });
            
             //dropdown
             let dropdown12 = document.querySelectorAll('.postedjobs');
            dropdown12.forEach(function(dropdown123) {
            dropdown123.addEventListener('click', function() {
            dropdown123.classList.toggle('active');
                });
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
                window.location.href = 'EmployerManage.php'; // Redirect to the final target page
            }, 1600); // Adjust the delay time as needed
        }
            </script>
        </body>
        </html>

