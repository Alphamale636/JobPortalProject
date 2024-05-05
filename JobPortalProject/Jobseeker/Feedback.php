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
   






// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['feedback'])) {
        // Get the feedback content from the form
        $feedbackContent = $_POST['feedback'];

        // Get the jobseeker_id using the session username
        if (isset($_SESSION['username'])) {
            $jobseekerUsername = $_SESSION['username'];
            $query = "SELECT user_id FROM jobseekerprofiles WHERE username = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $jobseekerUsername);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $jobseekerId);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            // Insert the feedback into the database
            $insertQuery = "INSERT INTO feedbacks (`jobseeker_id`, `feedbacktext`, `timestamp`) VALUES (?, ?, NOW())";
            $stmtInsert = mysqli_prepare($conn, $insertQuery);
            mysqli_stmt_bind_param($stmtInsert, "is", $jobseekerId, $feedbackContent);
            if (mysqli_stmt_execute($stmtInsert)) {
                // Feedback inserted successfully
                header("Location: Feedback.php?success=1");
            } else {
                // Handle the case where insertion failed
                echo "Error inserting feedback: " . mysqli_error($conn);
            }
        } else {
            echo "Error: User not logged in.";
        }
    }
}



?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Feedback</title>
        <link rel="stylesheet" type="text/css" href="css/feedback.css">
        <link rel="stylesheet" type="text/css" href="css/seekerdash.css">
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
                        <li class="active1">
                            <a href="Feedback.php" class="active2"><span class="icon"><ion-icon name="chatbubble-outline"></ion-icon></span>
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
                            <h1>Feedback</h1>
                        </div>
                        <!--userImg-->  
                        <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?> 
                    </div>
                    <div class="details">
                        <div class="Feedback">
                            <div class="FeedbackHeader">
                                <h2><ion-icon name="chatbubble-outline"></ion-icon> Feedback</h2>
                            <div class="textheader"><div class="text">Please Provide Your Feedback Here</div>
                            <div class="limit" id="charCount"></div>
                        </div>
                        <form id="feedbackForm" action="Feedback.php" method="POST">
                        <div class="textspace">
                            <div class="textbox">
                            <textarea name="feedback" id="myTextarea" placeholder="Type Here"></textarea> 
                            </div>
                     
                     </div>
                    <div class="submitbtn"><a href="#"  onclick="submitForm();">Submit</a></div>
                        </form>
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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
        <script src="js/logout.js"></script>
        <script src="js/highlightnavigation.js"></script>
        <script>
        //textarea adjust
            // Function to adjust textarea height based on content
         function adjustTextareaHeight()
          {
                const textarea = $("#myTextarea")[0];
                textarea.style.height = "auto"; // Reset height to auto
                textarea.style.height = `${textarea.scrollHeight}px`; // Set height based on content
            }

            // Function to count words and enforce word limit
            function countWords()
             {
            const textarea = $("#myTextarea")[0];
            const charCount = $("#charCount");

            const text = textarea.value;
            const wordCount = text.split(/\s+/).filter(Boolean).length;

            charCount.text(`Word Limit: ${wordCount}/200`); // Change 200 to your desired word limit

            if (wordCount > 200) { // Change 200 to your desired word limit
            const words = text.split(/\s+/).filter(Boolean).slice(0, 200).join(" ");
             textarea.value = words;
             charCount.text(`Word Limit Exceeded`); // Change 200 to your desired word limit
             }
            }

            // Attach the adjustTextareaHeight function to input events

             $("#myTextarea").on("input", function () {
                 adjustTextareaHeight();
                 countWords();
             });

            // Initial adjustment when the page loads
            adjustTextareaHeight();
            countWords();

            //MenuToggle
            let toggle = document.querySelector('.toggle');
            let navigation = document.querySelector('.navigation');
            let main = document.querySelector('.main');

            toggle.onclick = function(){
                navigation.classList.toggle('active');
                main.classList.toggle('active');
            }

            //formsubmit
                function submitForm() {
                // Get the form element by its ID
                var form = document.getElementById("feedbackForm");

                // Submit the form
                form.submit();
            }
//sweetalert    
            if (window.location.search.indexOf('success') > -1) {
                            Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Feedback Submitted Successfully',
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