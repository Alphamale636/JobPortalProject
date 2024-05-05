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
    

       
?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Resume</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"></script>
        <link rel="stylesheet" type="text/css" href="css/Resume.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" type="text/css" href="css/seekerdash.css">
        <link rel="stylesheet" type="text/css" href="css/logout.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
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
                        <li class="active1">
                            <a href="Resume.php" class="active2">
                            <span class="icon"><ion-icon name="document-text-outline"></ion-icon> </span>
                        <span class="nav-item">Resume</span>
                        </a>
                        </li>
                        <li class="menu">
                                <a href="Applications.php" > 
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
                            <h1>Resume</h1>
                        </div>
                        <!--userImg-->  
                        <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?> 
                    </div>
                    <div class="details">
                        <div class="Resume">
                            <div class="ResumeHeader">
                                <h2><ion-icon name="document-text-outline"></ion-icon> Resume</h2>
                            <div class="textheader">
                                <div class="text">Please Upload Your Resume Here</div>
                                <div class="ratingtext">Resume Rating</div>
                            </div>
                            
                            
                        <div class="Upload">
                            <div class="UploadCard">
                                <div class="card">
                                   <div class="iconbx">
                                    <ion-icon name="cloud-upload-outline"></ion-icon>
                                   </div>
                                 
                                   <input type="file" accept=" .txt" id="resumeFile">
                               </div>
   
                               <div class="card1">
                               <div id="ratingValue" class="Rating">Empty</div>
                                <canvas id="myChart"></canvas>
                                </div>
                            <div class="Uploadbtn" id="fileInput"><a href="#"><ion-icon name="cloud-upload-outline"></ion-icon>&nbsp Upload</a></div>
                            </div>
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
        <script src="js/my_chart.js"></script>
        <script src="js/logout.js"></script>
        <script src="js/highlightnavigation.js"></script>
        
   
            
            <script>
   
    document.getElementById('fileInput').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent the anchor tag from navigating

        // Get the file input element
        const fileInput = document.getElementById('resumeFile');
        const file = fileInput.files[0];

        if (file) {
            const formData = new FormData();
            formData.append('resume', file);

            // Use AJAX to send the file to your server
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'UploadResume.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);

                    if (response.success) {
                        // Show a success message using SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'Resume Uploaded Successfully',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error Updating Resume! Please Try Again',
                        });

                        if (!response.user) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'User Not Found!',
                            });
                        }

                        if (!response.file) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Invalid File Type! Please Choose A File with .txt or .pdf Extension!',
                            });
                        }

                        if (!response.notuploaded) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'No File Uploaded! Please Select A File!',
                            });
                        }
                    }
                } else {
                    // Handle errors (e.g., show a basic error message)
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'File upload failed. Please try again.',
                    });
                }
            };
            xhr.send(formData);
        } else {
            // Handle the case where no file was selected
            Swal.fire({
                icon: 'warning',
                title: 'No File Selected',
                text: 'Please select a file to upload.',
            });
        }
    });



            //MenuToggle
            let toggle = document.querySelector('.toggle');
            let navigation = document.querySelector('.navigation');
            let main = document.querySelector('.main');

            toggle.onclick = function(){
                navigation.classList.toggle('active');
                main.classList.toggle('active');
            }

            //sweetalert
            if (window.location.search.indexOf('upload') > -1) {
                Swal.fire({
                icon: 'success',
                title: 'Resume Uploaded Successfully',
                showConfirmButton: false,
                timer: 1500
            });
            setTimeout(function () {
                window.location.href = 'JobListings.php'; // Redirect to the final target page
            }, 1600); // Adjust the delay time as needed
        }


        //sweetalert
   if (window.location.search.indexOf('invalidfiletype') > -1) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Invalid File Type! Please Choose A File with .txt or .pdf Extension!',
        });
        setTimeout(function () {
            window.location.href = 'Resume.php'; // Redirect to the final target page
        }, 1700); // Adjust the delay time as needed
    }
  
    
        //sweetalert
   if (window.location.search.indexOf('Nofileuploaded') > -1) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'No File Selected! Please Select A File!',
        });
        setTimeout(function () {
            window.location.href = 'Resume.php'; // Redirect to the final target page
        }, 1700); // Adjust the delay time as needed
    }
            </script>
        </body>
</html>