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
  

        $seekerUsername = $_SESSION['username'];
            $query = "SELECT * FROM jobseekerprofiles WHERE username = '$seekerUsername'";
            $result = mysqli_query($conn, $query);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

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
            $parentDirectory = '../'; // Go back one directory
            $profilePictureURL2 = $parentDirectory . $profilePictureURL2;
            }


            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $contact = $_POST['contact'];
            $location = $_POST['location'];
            $skills = $_POST['skills'];
            $email1 = $_POST['email'];
            $experience = $_POST['experience'];
            $industry = $_POST['industry'];
            $jobtype= $_POST['jobtype'];
            $jobrole = $_POST['jobrole'];
            $salary = $_POST['salary'];
            $languages = $_POST['languages'];
            $availability = $_POST['availability'];
            $resume = $_POST['resume'];
        
               
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
                $query = "UPDATE jobseekerprofiles SET `first_name` = ?, `last_name` = ?, `email` = ?, `contact_number` = ?, `location` = ?, `resume` = ?, `skills` = ?, `profile_picture` = ?, `experience` = ?, `industry_preference` = ?, `job_type_preference` = ?, `job_role_preference` = ?, `salary_expectation` = ?, `languages` = ?, `availability` = ? WHERE `username` = ?";
        
                if ($stmt = mysqli_prepare($conn, $query)) {
                    // Bind the parameters and execute the query
                    
                    mysqli_stmt_bind_param($stmt, "ssssssssssssssss", $firstname, $lastname, $email1, $contact, $location, $resume, $skills, $profilepicture, $experience, $industry, $jobtype, $jobrole, $salary, $languages, $availability, $seekerUsername);
        
                    if (mysqli_stmt_execute($stmt)) {
                        // Data updated successfully
                        header('Location: ProfileSection.php?profileupdated=1');
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
        <link rel="stylesheet" type="text/css" href="css/ProfileSection.css">
        <link rel="stylesheet" type="text/css" href="css/seekerdash.css">
        <link rel="stylesheet" type="text/css" href="css/logout.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
        <style>
            table
            {
                position: relative;
                top: -10px;
            }
            table td{
                height: 45px;
            }
            table td input
            {
                position: relative;
                top:-5px;
            }
            table td textarea
{
    position: relative;
    width: 80%;
    top: -5px;
    height: 30px;
    outline:none;
    overflow: auto;
    border:none;
    resize: none;
    font-size: 1em;
    overflow-y: auto ;
}
.details .Profile table tr:nth-child(10)
{
    border-bottom: none;
}
select
{
    outline:none;
    border:none;
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
                                <a href="DashboardSection.php"> 
                        <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
                        <span class="nav-item">Dashboard</span>
                        </a>
                        </li>
                            <li class="active1">
                                <a href="ProfileSection.php" class="active2"> 
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
                            <h1>Profile</h1>
                        </div>
                        <!--userImg-->  
                        <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?>
                    </div>
                    <div class="details">
                        <div class="Profile">
                            <div class="ProfileHeader">
                                <ion-icon name="person-outline"></ion-icon> <h2>EDIT  PROFILE</h2>
                               <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?>
                            </div>
                            <table>
                                <form method="post" id="myForm" action="editProfile.php" enctype="multipart/form-data">
                           
                                <tr>
                                        <th>First Name</th>
                                        <td style="position:relative; top:1px;"><input type="text" class="inpbx" placeholder="Type Here.." name="firstname" style="outline: none; border: none; position:relative;" value="<?php echo $firstname;?>"> </td>
                                </tr>
                                <tr>
                                        <th>Last Name</th>
                                        <td style="position:relative; top:1px;"><input type="text" class="inpbx" placeholder="Type Here.." name="lastname" style="outline: none; border: none; position:relative; " value="<?php echo $lastname; ?>"> </td>
                                </tr>
                                <tr>
                                        <th>Profile Picture</th>
                                        <td> <input required="" type="file" name="profilepicture" accept="image/*" id="profilepicture" value='<?php $profilePictureURL?>' style="position:relative; width: 190px; top:-5px; overflow:hidden;"></td>
                                    </tr>
                                <tr>
                                        <th>Email</th>
                                        <td><input type="text" class="inpbx" placeholder="Type Here.." name="email" style="outline: none; border: none; " value="<?php echo $email1 ?>"></td>
                                </tr>
                                <tr>
                                        <th>Contact Number</th>
                                        <td><input type="text" class="inpbx" placeholder="Type Here.." name="contact" style="outline: none; border: none; " value="<?php echo $contact; ?>"></td>
                                </tr>
                                </tr>

                                <tr>
                                    <th>Location</th>
                                    <td><input type="text" class="inpbx" placeholder="Type Here.." name="location" style="outline: none; border: none; " value="<?php echo $location ?>"></td>
                                </tr>
                                <tr> <th>Experience Level</th>
                                    <td><select name="experience" style="position : relative; top:-5px;">
                                        <option value="<?php echo $experience; ?>"  selected><?php echo $experience; ?></option>
                                        <option value="0 years">0 year</option>
                                        <option value="1 year">1 year</option>
                                        <option value="2 years">2 years</option>
                                        <option value="3 years">3 years</option>
                                        <option value="3+ years">3+ years</option>
                                      </select>
                                      
                                    </td>
                                </tr>
                                <tr>
                                <th>Industry</th>
                                <td><select name="industry" style="position : relative; top:-5px;">
                                     <option value="<?php echo $industry; ?>" selected id="typetext"><?php echo $industry; ?></option>
                                    <option value="Software Dev">Software Dev</option>
                                    <option value="Telecommunications">Telecommunications</option>
                                    <option value="Finance">Finance</option>
                                  </select>
                                </td>
                                </tr>
                                <tr>
                                <th>Job Type</th>
                                <td><select name="jobtype" style="position : relative; top:-5px;">
                                    <option value="<?php echo $jobtype; ?>" selected><?php echo $jobtype; ?></option>
                                    <option value="Part-Time">Part-Time</option>
                                    <option value="Full-Time">Full-Time</option>
                                    <option value="Internship">Internship</option>
                                  </select>
                                </td>
                                </tr>
                                <tr>
                                        <th>Job Role Preference</th>
                                        <td><input type="text" class="inpbx" placeholder="Type Here.." name="jobrole" style="outline: none; border: none; " value="<?php echo $jobrole; ?>"></td>
                                </tr>
                                <tr>
                                <tr>
                                    <th>Skills</th>
                                    <td><textarea class="inpbx" placeholder="Type Here.." name="skills"><?php echo $skills; ?></textarea></td>
                                </tr>
                                <tr>
                                        <th>Salary Expectation</th>
                                        <td><input type="text" class="inpbx" placeholder="(e.g.$1000-$2000)" name="salary" style="outline: none; border: none; " value="<?php echo $salary; ?>"></td>
                                </tr>
                          <tr>
                               
                                        <th>Availability</th>
                                        <td> <select required class="select" id="Availability" name="availability" style="position : relative; top:-5px;">
                                        <option value="<?php echo $availability; ?>" selected><?php echo $availability; ?></option>
                                       
                                        <option value="9 AM - 6 PM">9 AM - 6 PM</option>
                                        <option value="9 PM - 6 AM">9 PM - 6 AM</option>
                                        </select></td>
                                </tr>
                                <tr>
                                        <th>Languages</th>
                                        <td><input type="text" class="inpbx" placeholder="Type Here.." name="languages" style="outline: none; border: none; " value="<?php echo $languages; ?>"></td>
                                </tr>
                               
                               
                                </form>
                            </table>
                            <div class="btns" style="position:relative; top:-25px">
                            <div class="edit" id="submit" ><a href="#"><ion-icon name="checkmark-outline" style="font-size:1.2em; top:3px; left:-2px;"></ion-icon>Save</a></div>&nbsp &nbsp
                            <div class="edit"><a href="ProfileSection.php" ><ion-icon name="close-outline" style="font-size:1.2em; top:4px; left: -2px;"></ion-icon>Cancel</a></div>&nbsp &nbsp
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
                var selects = document.getElementsByTagName('select');
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

                for (var i = 0; i < selects.length; i++) {
                    if (selects[i].value.trim() === '') {
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