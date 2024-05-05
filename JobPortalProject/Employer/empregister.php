<?php 
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $companyname = $_POST['companyname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['Password'];
    $contact = $_POST['contact-number'];
    $location = $_POST['location'];
    $website = $_POST['website'];
    $industry = $_POST['industry'];
    $aboutus = $_POST['aboutus'];
    $links = $_POST['links'];
    $joineddate = $_POST['joineddate'];

    $linksArray = explode(' ', $links);
    $links1 = implode("\n", $linksArray);
 
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

    $queryCheckUsername = "SELECT username FROM employerprofiles WHERE username = ?";
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
    $query = "INSERT INTO employerprofiles (`username`, `company_name`,  `email`, `password`, `logo`, `contact_number`, `location`,  `website`, `industry`, `about_us`, `links`, `joineddate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "ssssssssssss",$username, $companyname, $email, $hashedPassword, $profilepicture, $contact, $location, $website, $industry, $aboutus, $links1, $joineddate);

        if (mysqli_stmt_execute($stmt)) {


            $accountType = "Employer"; // Replace with "Employer" if it's an employer

            // Identify admin users by querying the adminprofiles table
            $queryAdmins = "SELECT admin_id FROM adminprofiles";
            $resultAdmins = mysqli_query($conn, $queryAdmins);

            // Prepare the insert query for notifications
            $insertQuery = "INSERT INTO notifications (`user_id`, `message`) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $insertQuery);

            // Insert notifications for each admin user
            while ($admin = mysqli_fetch_assoc($resultAdmins)) {
                $adminUserId = $admin['admin_id'];
                $notificationMessage = "$companyname joined as $accountType";

                // Bind and execute the prepared statement
                mysqli_stmt_bind_param($stmt, "is", $adminUserId, $notificationMessage);
                mysqli_stmt_execute($stmt);
            }

        // Registration successful
        header('Location: ../Emplogin.php?employer=1');
            
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
        <link rel="stylesheet" href="css/empregister.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    </head>
    <title>Register</title>
    <body>
        <form class="form" method="POST" enctype="multipart/form-data" id="myForm">
            <p class="title">Register </p>
            <p class="message">Signup now! </p>
                
                <label>
                    <input required="" placeholder="" type="text" class="input" name="companyname">
                    <span>Company Name</span>
                </label>
            <div class="flex">
                <label>
                    <input required="" placeholder="" type="text" class="input" name="username" id="username">
                    <span>Username</span>
                </label>
                <label>
                    <div class="dp">Logo
                    <input required="" type="file" name="profilepicture" accept="image/*" id="profilepicture">
                    </div>
                </label>
            </div>
     
            <div class="flex">
                <label>
                    <div class="select-container">
                        <select required class="select" id="industry" name="industry" id="industry">
                          <option value="" disabled selected id="typetext">Industry</option>
                          <option value="Software Dev">Software Dev</option>
                          <option value="Telecommunications">Telecommunications</option>
                          <option value="Finance">Finance</option>
                        </select>
                        <div class="custom-dropdown">
                          <span class="selected-option">Industry</span>
                          <ul class="options">
                            <li data-value="Software Dev">Software Dev</li>
                            <li data-value="Telecommunications">Telecommunications</li>
                            <li data-value="Finance">Finance</li>
                          </ul>
                        </div>
                      </div> 
                </label>
        
                <label>
                    <input required="" placeholder="" type="text" class="input" name="location" id="location">
                    <span>Location </span>
                </label>
             </div>
                <label>
                    <input required="" placeholder="" type="text" class="input" name="website">
                    <span>Website</span>
                </label>
            
                <label>
                    <input required="" placeholder="" type="text" class="input" name="links">
                    <span>Links</span>
                </label>
              
            
                <label>
                    <input required="" placeholder="" type="text" class="input" name="aboutus">
                    <span>About us..</span>
                </label>
   
              
            
            <label>
                <input required="" placeholder="" type="text" class="input" name="contact-number">
                <span>Contact-number</span>
            </label> 
            
                    
            <label>
                <input required="" placeholder="" type="email" class="input" name="email">
                <span>Email</span>
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
            <p class="signin">Already have an acount ? <a href="../Emplogin.php">Sign in!</a> </p>
        </form>
        <script>
//Job type dropdown
            const select = document.getElementById('industry');
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

// JavaScript to populate the posteddate field with the current date (date only)
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
                        alert("Please make sure your password contains at least 8 characters, an uppercase letter, a number, and a special character.");
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
//already existing username
                    document.addEventListener("DOMContentLoaded", function () {
        // Check if the $alertMessage is set and display the SweetAlert
        <?php if (isset($alertMessage)) : ?>
        Swal.fire("<?php echo $alertMessage; ?>");
        <?php endif; ?>
    });
   

        </script>
    </body>
</html>


