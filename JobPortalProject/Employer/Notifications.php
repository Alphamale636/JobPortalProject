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

        $employerUsername = $_SESSION['username'];

        // Fetch the employer's ID using the username
        $queryEmployerId = "SELECT employer_id FROM employerprofiles WHERE username = ?";
        $stmtEmployerId = mysqli_prepare($conn, $queryEmployerId);
        mysqli_stmt_bind_param($stmtEmployerId, "s", $employerUsername);
        mysqli_stmt_execute($stmtEmployerId);
        mysqli_stmt_bind_result($stmtEmployerId, $employerId);
        mysqli_stmt_fetch($stmtEmployerId);
        mysqli_stmt_close($stmtEmployerId);

        
        $countQuery = "SELECT COUNT(*) AS totalNotifications FROM notifications WHERE employer_id = $employerId";
        $result1 = mysqli_query($conn, $countQuery);

        if ($result1) {
            $row = mysqli_fetch_assoc($result1);
            $totalNotifications = $row['totalNotifications'];
        } else {
            $totalNotifications = 0; // Error handling if the query fails
        }


        ?>
        
        



<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Notifications</title>
        <link rel="stylesheet" type="text/css" href="css/Notifications.css">
        <link rel="stylesheet" type="text/css" href="css/empdash.css">
        <link rel="stylesheet" type="text/css" href="css/logout.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
        <script>
              //close icon
              function deleteNotification(notificationId) {
                // Make an AJAX request to delete the notification
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (xhttp.readyState === 4) {
                        if (xhttp.status === 200) {
                            console.log("Response: " + xhttp.responseText);
                            // Request was successful
                            var response = JSON.parse(xhttp.responseText);
                            if (response.success) {
                                // Deletion was successful, update the UI
                                var notificationDiv = document.getElementById('notification_' + notificationId);
                                if (notificationDiv) {
                                    notificationDiv.style.display = 'none';
                                }
                                // Update the totalNotifications count
                                var totalNotificationsCount = document.getElementById('totalNotificationsCount');
                                    if (totalNotificationsCount) {
                                        totalNotificationsCount.textContent = parseInt(totalNotificationsCount.textContent) - 1;
                                    }
                            } else {
                                // Handle the case where deletion failed
                                console.error(response.error);
                            }
                        } else {
                            // Handle other HTTP status codes (e.g., 404, 500)
                            console.error("Request failed with status: " + xhttp.status);
                        }
                    }
                };
                xhttp.open("POST", "DeleteNotification.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("notification_id=" + notificationId);
            }
            
            </script>
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
                       
                        <li class="active1">
                            <a href="Notifications.php" class="active2">
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
                            <h1>Notifications</h1>
                        </div>
                        <!--userImg-->  
                        <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?>
                    </div>
                    <div class="details">
                        <div class="Notification">
                            <div class="NotificationHeader">
                                <h2><ion-icon name="notifications-outline"></ion-icon> Notifications</h2>
                            <div class="textheader"><div class="text">You Have <a href="#"><span id="totalNotificationsCount"><?php echo $totalNotifications ?></span></a> Notifications</div>
                           
                        <div class="all"><a href="#" id="clearAllNotifications">Clear All</a></div>
                        </div>
                        <div class="cardbox">
                        <?php
                        $query5 = "SELECT * FROM notifications WHERE employer_id = $employerId";
                        $result5 = mysqli_query($conn, $query5);

                        if ($totalNotifications > 0) {
                            if ($result5) {
                                while ($row = mysqli_fetch_assoc($result5)) {
                                    $id = $row['notification_id'];
                                    $message = $row['message']; // Adjust the column name according to your database structure
                        
                                    ?>
                            <div class="card" id="notification_<?php echo $id; ?>">
                                    <div class="message" ><a href="#" style="text-decoration:none"><?php echo $message; ?></a></div>

                                    
                                 
                                  <ion-icon name="close-outline" onclick="deleteNotification(<?php echo $id; ?>)"></ion-icon> 
                                          
                        </div>
                        <?php
                    }
                }
                } else { ?>
                    <?php echo "No Notification Found!";?>
                    <?php
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



//clearAll
            var clearAllNotificationsLink = document.getElementById('clearAllNotifications');
            if (clearAllNotificationsLink) {
                clearAllNotificationsLink.addEventListener('click', function (event) {
                    event.preventDefault(); // Prevent the default link behavior

                    // Make an AJAX request to clear all notifications for the user
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function () {
                        if (xhttp.readyState === 4) {
                            if (xhttp.status === 200) {
                                // Request was successful
                                var response = JSON.parse(xhttp.responseText);
                                if (response.success) {
                                    // Remove all notification cards from the UI
                                    var notificationContainer = document.querySelector('.cardbox');
                                    if (notificationContainer) {
                                        notificationContainer.innerHTML = ''; // Clear all notification cards
                                    }

                                    // Update the totalNotifications count to 0
                                    var totalNotificationsCount = document.getElementById('totalNotificationsCount');
                                    if (totalNotificationsCount) {
                                        totalNotificationsCount.textContent = 0;
                                    }
                                } else {
                                    // Handle the case where clearing notifications failed
                                    console.error(response.error);
                                }
                            } else {
                                // Handle other HTTP status codes (e.g., 404, 500)
                                console.error("Request failed with status: " + xhttp.status);
                            }
                        }
                    };
                    xhttp.open("POST", "ClearNotifications.php", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.send("employer_id=" + '<?php echo $employerId; ?>');

                });
            }

   
            </script>
        </body>
</html>