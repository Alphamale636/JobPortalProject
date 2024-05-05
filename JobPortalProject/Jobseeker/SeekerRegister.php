<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $location = $_POST['location'];
    $industry = $_POST['industry'];
    $job_type = $_POST['jobtype'];
    $job_role = $_POST['jobrole'];
    $languages = $_POST['languages'];
    $salary_expectation = $_POST['salary'];
    $availability = $_POST['availability'];
    $skills = $_POST['skills'];
    $experience = $_POST['experience'];
    $contact = $_POST['contact-number'];
    $email = $_POST['email'];
    $password = $_POST['Password'];
    $joineddate = $_POST['joineddate'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Handle file upload
    if ($_FILES['profilepicture']['error'] === 0) {
        $uploadDir = '../uploads/';
        $uploadFile = $uploadDir . basename($_FILES['profilepicture']['name']);

        if (move_uploaded_file($_FILES['profilepicture']['tmp_name'], $uploadFile)) {
            // File upload successful
            $profilepicture = $uploadFile;
        } else {
            // File upload failed
            $profilepicture = 'images/avatar1.png'; // You can set a default image or handle this case accordingly
        }
    } else {
        // No file uploaded or an error occurred
        $profilepicture = 'images/avatar1.png'; // You can set a default image or handle this case accordingly
    }

    // Your database connection code
    $dbHost = "localhost";
    $dbUser = "root";
    $dbPassword = "";
    $dbName = "jobportal";

    $conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Check if the username already exists
    $queryCheckUsername = "SELECT username FROM jobseekerprofiles WHERE username = ?";
    if ($stmtCheckUsername = mysqli_prepare($conn, $queryCheckUsername)) {
        mysqli_stmt_bind_param($stmtCheckUsername, "s", $username);

        if (mysqli_stmt_execute($stmtCheckUsername)) {
            mysqli_stmt_store_result($stmtCheckUsername);

            if (mysqli_stmt_num_rows($stmtCheckUsername) > 0) {
                // Username already exists, handle the error
                $alertMessage = "Username already exists. Please choose a different username.";
            } else {
                // Username is unique, proceed with registration

                // Insert the data into the database
                $query = "INSERT INTO jobseekerprofiles (`username`, `first_name`, `last_name`, `email`, `password`, `profile_picture`, `contact_number`, `location`,  `skills`, `experience`, `industry_preference`, `job_type_preference`, `job_role_preference`, `salary_expectation`, `languages`, `availability`, `joineddate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                if ($stmt = mysqli_prepare($conn, $query)) {
                    mysqli_stmt_bind_param($stmt, "sssssssssssssssss", $username, $firstname, $lastname, $email, $hashedPassword, $profilepicture, $contact, $location, $skills, $experience, $industry, $job_type, $job_role, $salary_expectation, $languages, $availability, $joineddate);

                    if (mysqli_stmt_execute($stmt)) {
                        $fullname = $firstname . ' ' . $lastname; // Replace with the actual user's name
                        $accountType = "Job Seeker"; // Replace with "Employer" if it's an employer

                        // Identify admin users by querying the adminprofiles table
                        $queryAdmins = "SELECT admin_id FROM adminprofiles";
                        $resultAdmins = mysqli_query($conn, $queryAdmins);

                        // Prepare the insert query for notifications
                        $insertQuery = "INSERT INTO notifications (`user_id`, `message`) VALUES (?, ?)";
                        $stmt = mysqli_prepare($conn, $insertQuery);

                        // Insert notifications for each admin user
                        while ($admin = mysqli_fetch_assoc($resultAdmins)) {
                            $adminUserId = $admin['admin_id'];
                            $notificationMessage = "$fullname joined as $accountType";

                            // Bind and execute the prepared statement
                            mysqli_stmt_bind_param($stmt, "is", $adminUserId, $notificationMessage);
                            mysqli_stmt_execute($stmt);
                        }

                        // Registration successful
                        header('Location: ../Login.php?Jobseeker=1');
                        exit();
                    } else {
                        // Registration failed
                        $error = "Error: " . mysqli_error($conn);
                    }

                    mysqli_stmt_close($stmt);
                } else {
                    $error = "Database query error. Please try again later.";
                }
            }

            mysqli_stmt_close($stmtCheckUsername);
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    } else {
        $error = "Database query error. Please try again later.";
    }

    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="css/SeekerRegister.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    </head>
    <title>Register</title>
    <body>
        <form class="form" method="POST" enctype="multipart/form-data" id="myForm">
            <p class="title">Register </p>
            <p class="message">Signup now! </p>
                <div class="flex">
                <label>
                    <input required="" placeholder="" type="text" class="input" name="firstname">
                    <span>Firstname</span>
                </label>
        
                <label>
                    <input required="" placeholder="" type="text" class="input" name="lastname" >
                    <span>Lastname</span>
                </label>
            </div>  
     
            <div class="flex">
                <label>
                <div class="select-container2">
                        <select required class="select" id="industry" name="industry">
                          <option value="" disabled selected id="typetext">Industry</option>
                          <option value="Software Dev">Software Dev</option>
                          <option value="Telecommunications">Telecommunications</option>
                          <option value="Finance">Finance</option>
                        </select>
                        <div class="custom-dropdown">
                          <span class="selected-option2">Industry</span>
                          <ul class="options2">
                            <li data-value="Software Dev">Software Dev</li>
                            <li data-value="Telecommunications">Telecommunications</li>
                            <li data-value="Finance">Finance</li>
                          </ul>
                        </div>
                      </div> 
                </label>
        
                <label>
                    <div class="select-container">
                        <select required class="select" id="job-type" name="jobtype">
                          <option value="" disabled selected id="typetext">Job Type</option>
                          <option value="Part-Time">Part-Time</option>
                          <option value="Full-Time">Full-Time</option>
                          <option value="Internship">Internship</option>
                        </select>
                        <div class="custom-dropdown">
                          <span class="selected-option">Job Type</span>
                          <ul class="options">
                            <li data-value="Part-Time">Part-Time</li>
                            <li data-value="Full-Time">Full-Time</li>
                            <li data-value="Internship">Internship</li>
                          </ul>
                        </div>
                      </div>
                      
                    
                </label>
            </div>  
            <div class="flex">
                <label>
                    <input required="" placeholder="" type="text" class="input" name="jobrole">
                    <span>Job Role </span>
                </label>
        
                <label>
                    <input required="" placeholder="" type="text" class="input" name="location">
                    <span>Location </span>
                </label>
            </div> 
            <div class="flex">
                <label>
                    <input required="" placeholder="" type="text" class="input" name="languages">
                    <span>Languages </span>
                </label>
        
                <label>
                    <input required="" placeholder="" type="text" class="input" name="salary">
                    <span>Salary(e.g.:$100-$1000) </span>
                </label>
            </div>
            <div class="flex">
                <label>
                <div class="select-container3">
                    <select required class="select" id="experience" name="experience">
                          <option value="" disabled selected id="typetext">Experience</option>
                          <option value="0 years">0 year</option>
                          <option value="1 year">1 year</option>
                          <option value="2 years">2 years</option>
                          <option value="3 years">3 years</option>
                          <option value="3+ years">3+ years</option>
                    </select>
                        <div class="custom-dropdown">
                          <span class="selected-option3">Experience</span>
                          <ul class="options3">
                          <li data-value="0 years">0 years</li>
                            <li data-value="1 year">1 year</li>
                            <li data-value="2 years">2 years</li>
                            <li data-value="3 years">3 years</li>
                            <li data-value="3+ years">3+ years</li>
                          </ul>
                        </div>
                      </div>
                </label>
        
                <label>
                    <div class="dp">Profile Picture
                    <input required="" type="file" name="profilepicture" accept="image/*" id="profilepicture">
                    </div>
                </label>
            </div>
            <div class="flex">
                <label>
                    <div class="select-container1">
                        <select required class="select" id="Availability" name="availability">
                          <option value="" disabled selected>Availability</option>
                          <option value="option1"></option>
                          <option value="9 AM - 6 PM">9 AM - 6 PM</option>
                          <option value="9 PM - 6 AM">9 PM - 6 AM</option>
                        </select>
                        <div class="custom-dropdown">
                          <span class="selected-option1">Availability</span>
                          <ul class="options1">
                            <li data-value="9 AM - 6 PM">9 AM - 6 PM</li>
                            <li data-value="9 PM - 6 AM">9 PM - 6 AM</li>
                          </ul>
                        </div>
                      </div>
                      
                    
                </label>
                <label>
                    <input required="" placeholder="" type="text" class="input" name="skills">
                    <span>Skills</span>
                </label>
            </div>
            <label>
                <input required="" placeholder="" type="text" class="input" name="contact-number">
                <span>Contact-number</span>
            </label> 
            
                    
            <label>
                <input required="" placeholder="" type="email" class="input" name="email">
                <span>Email</span>
            </label> 
                       
            <label>
                    <input required="" placeholder="" type="text" class="input" name="username">
                    <span>username</span>
                </label>
        
                
           
            <label>
                <input required="" placeholder="" type="password" class="input" name="Password" id="password">
                <span>Password</span>
            </label>
            <label>
                <input required="" placeholder="" type="password" class="input" name="confirmPassword">
                <span>Confirm password</span>
                <input type="hidden" name="joineddate" id="joineddate">
            </label>
            <button class="submit">Submit</button>
            <p class="signin">Already have an acount ? <a href="../Login.php">Sign in!</a> </p>
        </form>
        <script>
//Job type dropdown
            const select = document.getElementById('job-type');
            const selectedOption = document.querySelector('.selected-option');
            const optionsList = document.querySelector('.options');

            // Show the custom dropdown when clicking the selected option
            selectedOption.addEventListener('click', () => {
            optionsList.style.display = 'block';
            });

            // Handle selecting an option
            optionsList.addEventListener('click', (e) => {
            if (e.target.tagName === 'LI') {
            selectedOption.textContent = e.target.textContent;
            select.value = e.target.getAttribute('data-value');
            optionsList.style.display = 'none';
            }
            });

            // Hide the custom dropdown when clicking outside of it
            document.addEventListener('click', (e) => {
            if (!e.target.closest('.select-container')) {
            optionsList.style.display = 'none';
             }
            });

            // Set initial selected option text
            selectedOption.textContent = select.options[select.selectedIndex].textContent;
            

//availability Dropdown
            const select1 = document.getElementById('Availability');
            const selectedOption1 = document.querySelector('.selected-option1');
            const optionsList1 = document.querySelector('.options1');

            // Show the custom dropdown when clicking the selected option
            selectedOption1.addEventListener('click', () => {
            optionsList1.style.display = 'block';
            });

            // Handle selecting an option
            optionsList1.addEventListener('click', (e) => {
            if (e.target.tagName === 'LI') {
            selectedOption1.textContent = e.target.textContent;
            select1.value = e.target.getAttribute('data-value');
            optionsList1.style.display = 'none';
            }
            });

            // Hide the custom dropdown when clicking outside of it
            document.addEventListener('click', (e) => {
            if (!e.target.closest('.select-container1')) {
            optionsList1.style.display = 'none';
             }
            });

            // Set initial selected option text
            selectedOption1.textContent = select1.options[select1.selectedIndex].textContent;

//industry Dropdown
            const select2 = document.getElementById('industry');
            const selectedOption2 = document.querySelector('.selected-option2');
            const optionsList2 = document.querySelector('.options2');

            // Show the custom dropdown when clicking the selected option
            selectedOption2.addEventListener('click', () => {
            optionsList2.style.display = 'block';
            });

            // Handle selecting an option
            optionsList2.addEventListener('click', (e) => {
            if (e.target.tagName === 'LI') {
            selectedOption2.textContent = e.target.textContent;
            select2.value = e.target.getAttribute('data-value');
            optionsList2.style.display = 'none';
            }
            });

            // Hide the custom dropdown when clicking outside of it
            document.addEventListener('click', (e) => {
            if (!e.target.closest('.select-container2')) {
            optionsList2.style.display = 'none';
             }
            });

            // Set initial selected option text
            selectedOption2.textContent = select2.options[select2.selectedIndex].textContent;

//Experience Dropdown
            const select3 = document.getElementById('experience');
            const selectedOption3 = document.querySelector('.selected-option3');
            const optionsList3 = document.querySelector('.options3');

            // Show the custom dropdown when clicking the selected option
            selectedOption3.addEventListener('click', () => {
            optionsList3.style.display = 'block';
            });

            // Handle selecting an option
            optionsList3.addEventListener('click', (e) => {
            if (e.target.tagName === 'LI') {
            selectedOption3.textContent = e.target.textContent;
            select3.value = e.target.getAttribute('data-value');
            optionsList3.style.display = 'none';
            }
            });

            // Hide the custom dropdown when clicking outside of it
            document.addEventListener('click', (e) => {
            if (!e.target.closest('.select-container3')) {
            optionsList3.style.display = 'none';
             }
            });

            // Set initial selected option text
            selectedOption3.textContent = select3.options[select3.selectedIndex].textContent;


//Form check
            document.addEventListener("DOMContentLoaded", function () {
            const form = document.querySelector(".form");
            const passwordInput = document.querySelector('input[name="Password"]');
            const confirmPasswordInput = document.querySelector('input[name="confirmPassword"]');
            const submitButton = document.querySelector(".submit");

                form.addEventListener("submit", function (event) {
                    let valid = true;

                    // Check if all required fields are filled
                    const inputs = form.querySelectorAll("input[required]");
                    inputs.forEach(function (input) {
                    if (!input.value.trim()) {
                        valid = false;
                        input.classList.add("error");
                    } else {
                        input.classList.remove("error");
                    }
                    });

                    // Check if passwords match
                    if (passwordInput.value !== confirmPasswordInput.value) {
                    valid = false;
                    passwordInput.classList.add("error");
                    confirmPasswordInput.classList.add("error");
                    
                    } else {
                    passwordInput.classList.remove("error");
                    confirmPasswordInput.classList.remove("error");
                    }

                    if (!valid) {
                    event.preventDefault();
                    }
                });
                });
//Salary Validation
                // JavaScript validation
                document.addEventListener("DOMContentLoaded", function () {
                const form = document.querySelector(".form");
                const salaryInput = document.querySelector('input[name="salary"]');
    
                form.addEventListener("submit", function (event) {
                 const salaryPattern = /^\$[0-9]+-\$[0-9]+$/;
        
            if (!salaryPattern.test(salaryInput.value)) {
                Swal.fire('Please enter a valid salary range in the format $100-$1000');
                event.preventDefault(); // Prevent form submission
        }
    });
});

// JavaScript to populate the jooineddate field with the current date (date only)
                    document.getElementById("myForm").addEventListener("submit", function() {
                    const currentDate = new Date().toISOString().split("T")[0]; // Format: yyyy-mm-dd
                    document.getElementById("joineddate").value = currentDate;
                    });

//password validity check
                    document.getElementById("myForm").addEventListener("submit", function(event) {
                    const password = document.getElementById("password").value; // Get the password input value

                    if (!validatePassword(password)) {
                        // Password does not meet the requirements
                        event.preventDefault(); // Prevent form submission
                        Swal.fire('Please make sure your password contains at least 8 characters, an uppercase letter, a number, and a special character.');
                    }
                    });

                    function validatePassword(password) {
                    // Regular expressions for password requirements
                    const lengthRegex = /.{8,}/; // At least 8 characters
                    const uppercaseRegex = /[A-Z]/; // At least one uppercase letter
                    const digitRegex = /\d/; // At least one digit
                    const specialCharRegex = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/; // At least one special character

                    // Check if all requirements are met
                    const isLengthValid = lengthRegex.test(password);
                    const isUppercaseValid = uppercaseRegex.test(password);
                    const isDigitValid = digitRegex.test(password);
                    const isSpecialCharValid = specialCharRegex.test(password);

                    // Return true if all requirements are met, false otherwise
                    return isLengthValid && isUppercaseValid && isDigitValid && isSpecialCharValid;
                    }
                    
         document.addEventListener("DOMContentLoaded", function () {
        // Check if the $alertMessage is set and display the SweetAlert
        <?php if (isset($alertMessage)) : ?>
        Swal.fire("<?php echo $alertMessage; ?>");
        <?php endif; ?>
    });
   

        </script>
    </body>
</html>


