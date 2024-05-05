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
        $query = "SELECT employer_id, company_name, email, logo, contact_number, location, website, about_us, links, industry FROM employerprofiles WHERE username = '$employerUsername'";
        $result = mysqli_query($conn, $query);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $employer_id = $row['employer_id'];
            $company_name = $row['company_name'];
            $email = $row['email'];
            $logo = $row['logo'];
            $contact_number = $row['contact_number'];
            $location = $row['location'];
            $website = $row['website'];
            $about_us = $row['about_us'];
            $links = $row['links'];
            $industry = $row['industry'];

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $companyname = $_POST['companyname'];        
                $email = $_POST['email'];
                $contact = $_POST['contact-number'];
                $location = $_POST['location'];
                $website = $_POST['website'];
                $aboutus = $_POST['aboutus'];
                $links = $_POST['links'];
               
             
             
            
                if ($_FILES['profilepicture']['error'] === 0) {
                    $uploadDir = '../uploads/';
                    $uploadFile = $uploadDir . basename($_FILES['profilepicture']['name']);
                    
                    if (move_uploaded_file($_FILES['profilepicture']['tmp_name'], $uploadFile)) {
                        // File upload successful
                        $profilepicture = $uploadFile;
                    } else {
                        // File upload failed
                        $profilepicture = $profilePictureURL; // You can set a default image or handle this case accordingly
                    }
                } else {
                    // No file uploaded or an error occurred
                    $profilepicture = $profilePictureURL; // You can set a default image or handle this case accordingly
                }
        
                // Prepare the UPDATE query
                $query = "UPDATE employerprofiles SET `company_name` = ?, `email` = ?, `contact_number` = ?, `location` = ?, `website` = ?, `about_us` = ?, `logo` = ?, `links` = ? WHERE `username` = ?";
        
                if ($stmt = mysqli_prepare($conn, $query)) {
                    // Bind the parameters and execute the query
                    
                    mysqli_stmt_bind_param($stmt, "sssssssss", $companyname, $email, $contact, $location, $website, $aboutus, $profilepicture, $links, $employerUsername);
        
                    if (mysqli_stmt_execute($stmt)) {
                        // Data updated successfully
                        header('Location: Profile.php?profileupdated=1');
                    } else {
                        // Failed to update data
                        echo "Error: " . mysqli_error($conn);
                    }
        
                    mysqli_stmt_close($stmt);
                } else {
                    // Database query error
                    echo "Database query error. Please try again later.";
                }
            }

        ?>
        
        
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile</title>
        <link rel="stylesheet" type="text/css" href="css/Profile.css">
        <link rel="stylesheet" type="text/css" href="css/empdash.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
        <link rel="stylesheet" type="text/css" href="css/logout.css">
        <style>
            input
            {
                position: relative;
                width: 60%
            }
            </style>
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
                            <li class="active1">
                                <a href="Profile.php" class="active2"> 
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
                            <h1>Profile</h1>
                        </div>
                        <!--userImg-->  
                        <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?> 
                    </div>
                    <div class="details">
                        <div class="Profile">
                            <div class="ProfileHeader">
                                <ion-icon name="person-outline"></ion-icon> <h2>EDIT PROFILE</h2>
                                <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?> 
                            </div>
                            <table>
                            <form method="post" id="myForm" action="editProfile.php" enctype="multipart/form-data">
                            <tr>
                                        <th>Company Name</th>
                                        <td style="position:relative; top:1px;"><input type="text" class="inpbx" placeholder="Type Here.." name="companyname" style="outline: none; border: none; position:relative;" value="<?php echo $company_name;?>"> </td>
                                </tr>
                                <tr>
                                        <th>Profile Picture</th>
                                        <td> <input required="" type="file" name="profilepicture" accept="image/*" id="profilepicture" value='<?php $profilePictureURL?>' style="position:relative; width: 190px; top:1px; overflow:hidden;"></td>
                                    </tr>
                                <tr>
                                        <th>Email</th>
                                        <td style="position:relative; top:1px;"><input type="text" class="inpbx" placeholder="Type Here.." name="email" style="outline: none; border: none; position:relative;" value="<?php echo $email;?>"> </td>
                                </tr>
                                <tr>
                                        <th>Contact Number</th>
                                        <td style="position:relative; top:1px;"><input type="text" class="inpbx" placeholder="Type Here.." name="contact-number" style="outline: none; border: none; position:relative;" value="<?php echo $contact_number;?>"> </td>
                                </tr>
                                </tr> <tr>
                                    <th>Location</th>
                                    <td style="position:relative; top:1px;"><input type="text" class="inpbx" placeholder="Type Here.." name="location" style="outline: none; border: none; position:relative;" value="<?php echo $location;?>"> </td>
                                </tr>
                                <tr>
                                <th>Website</th>
                                    <td style="position:relative; top:-10px;">
                                        <div class="multiline-links" style="width: 60%; height: 50px;" >
                                        <textarea style=" height: 30px;" placeholder="Type Here.." name="website"><?php echo $website;?></textarea>
                                    </div>
                                    </td>
                                </tr>
                                <tr>
                                <th>About Us</th>
                                <td><textarea class="inpbx" placeholder="Type Here.." name="aboutus"><?php echo $about_us; ?></textarea></td>
                                </tr>
                               
                                <tr>
                                <th>Links</th>
                                    <td style="position:relative; top:-15px;">
                                        <div class="multiline-links"  style="width: 60%; height: 50px;">
                                        <textarea placeholder="Type Here.." name="links" ><?php echo $links;?></textarea>
                                       
                                    </div>
                                    </td>
                                </tr>
                                </form>
                            </table>
                            <?php
                        } else {
                                echo 'Job seeker not found.';
                            }
                        ?>
                            <div class="btns" style="position:relative; top:15px">
                            <div class="edit" id="submit" ><a href="#"><ion-icon name="checkmark-outline" style="font-size:1.2em; top:3px; left:-2px;"></ion-icon>Save</a></div>&nbsp &nbsp
                            <div class="edit"><a href="Profile.php" ><ion-icon name="close-outline" style="font-size:1.2em; top:4px; left: -2px;"></ion-icon>Cancel</a></div>&nbsp &nbsp
                            <div class="password"><a href="ChangePass.php" >Change Password <ion-icon name="create-outline"></ion-icon></a></div>
                           
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
   // trigger the submit button
   document.getElementById("submit").addEventListener("click", function(e) {
                e.preventDefault();
                validateForm();
            });

          
               function validateForm() {
                var inputs = document.getElementsByTagName('input');
                var textareas = document.getElementsByTagName('textarea');

                for (var i = 0; i < inputs.length; i++) {
                    // Skip validation for file inputs
                    if (inputs[i].type === 'file') {
                        continue;
                    }

                    if (inputs[i].value.trim() === '') {
                        Swal.fire('Please Fill In All The Input Fields!');
                        return false;
                    }
                }

                for (var i = 0; i < textareas.length; i++) {
                    if (textareas[i].value.trim() === '') {
                        Swal.fire('Please Fill In All The Input Fields!');
                        return false;
                    }
                }

                // Trigger the form submission
                document.getElementById("myForm").submit();
            }

            </script>
        </body>
</html>