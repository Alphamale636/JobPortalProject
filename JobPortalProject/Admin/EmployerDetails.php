<?php
include('../config.php');
            if (isset($_SESSION['username'])) {
            $adminUsername = $_SESSION['username'];
            $query = "SELECT * FROM adminprofiles WHERE username = '$adminUsername'";
            $result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $name = $row['fullname'];
    $email = $row['email'];
    $contactNumber = $row['contact_number'];
    $officeAddress = $row['office_address'];
    $aboutMe = $row['about_me'];
    $links = $row['links'];
}
}

                            // Retrieve the id from URL parameters
                            $employerId = $_GET['employer_id'];
                            
                            // Query the database for the specific job seeker
                            $employerId = mysqli_real_escape_string($conn, $employerId);
                            

                            $query = "SELECT * FROM `employerprofiles` WHERE `employer_id` = '$employerId'";
                            $result = $conn->query($query);

                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                            
                                // Retrieve job seeker details
                                $companyName = $row['company_name'];
                                $contact = $row['contact_number'];
                                $email1 = $row['email'];
                                $website = $row['website'];
                                $location = $row['location'];
                                $industry = $row['industry'];
                                $aboutus = $row['about_us'];
                                $links = $row['links'];
                                $logo = $row['logo'];
                                


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
        <title>Employer Manage</title>
        <link rel="stylesheet" type="text/css" href="css/EmployerDetails.css">
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
                        <li >
                            <a href="Jobseekermanage.php">
                            <span class="icon"> <ion-icon name="people-outline"></ion-icon> </i></span>
                        <span class="nav-item">Manage Job Seekers</span>
                        </a>
                        </li>
                        <li class="active1">
                            <a href="EmployerManage.php"  class="active2">
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
                            <h1>Manage Employers</h1>
                        </div>
                        <!--userImg-->  
                        <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?>
                    </div>
                    <div class="details">
                        <div class="Profile">
                            <div class="ProfileHeader">
                                <ion-icon name="person-outline"></ion-icon> <h2>EMPLOYER PROFILE</h2>
                                <?php echo '<div class="user"><img src="' . $logo . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?>
                            </div>
                            
                            <table>
                            <tr>
                                        <th>Company Name</th>
                                        <td style="position:relative; top:1px;"><?php echo $companyName; ?> </td>
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
                                <th>Website</th>
                                    <td style="position:relative; top:-15px;">
                                        <div class="multiline-links">
                                        <?php
                                                // Split the links by line break and create anchor tags
                                                $linksArray1 = explode("\n", $website);
                                                foreach ($linksArray1 as $website1) {
                                                echo "<a href='$website1'>$website1</a><br>";
                                                }
                                                ?>
                                    </div>
                                    </td>
                                </tr>
                                <tr>
                                <th>About Us</th>
                                    <td><textarea readonly><?php echo $aboutus; ?>
                                    </textarea></td>
                                </tr>
                               
                                <tr>
                                <th>Links</th>
                                    <td style="position:relative; top:-15px;">
                                        <div class="multiline-links">
                                        <?php
                                                // Split the links by line break and create anchor tags
                                                $linksArray = explode("\n", $links);
                                                foreach ($linksArray as $link) {
                                                echo "<a href='$link'>$link</a><br>";
                                                }
                                                ?>
                                    </div>
                                    </td>
                                </tr>
                               
                            </table>
                            <?php
                        } else {
                                echo 'Job seeker not found.';
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
                title: 'Are You Sure You Want To Delete This Account?',
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'No, keep it',
            }).then((result) => {
                if (result.isConfirmed) {
                    // User confirmed, proceed with deletion
                    var form = document.createElement("form");
                    form.method = "POST";
                    form.action = "DeleteEmployer.php";

                    var firstnameInput = document.createElement("input");
                    firstnameInput.type = "hidden";
                    firstnameInput.name = "employer_id";
                    firstnameInput.value = "<?php echo $employerId; ?>";

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