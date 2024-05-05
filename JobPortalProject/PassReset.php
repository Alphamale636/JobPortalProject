<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jobportal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the user's input (password and token)
    $newPassword = $_POST['password'];
    $token = $_POST['token'];

    // Check if the token exists in the database
    $sql = "SELECT `username` FROM employerprofiles WHERE reset_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        // Token is valid, update the password for the user
        $stmt->bind_result($username);
        $stmt->fetch();

        // Hash the new password before updating it in the database
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Update the user's password and clear the reset token
        $sqlUpdate = "UPDATE employerprofiles SET password = ?, reset_token = NULL WHERE username = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("ss", $hashedPassword, $username);
        
        if ($stmtUpdate->execute()) {
            // Password updated successfully
            header('Location: Emplogin.php?passwordreset=1');
        } else {
            // Error updating password
            echo '<script>alert("Error resetting password. Please try again later.");</script>';
        }
    } else {
        // Token is not valid
        echo '<script>alert("Invalid or expired token. Please request a new password reset link.");</script>';
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="PassReset.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    </head>
    <title>Reset Password</title>
    <body>
        <form class="form" method="POST" enctype="multipart/form-data" id="myForm">
            <p class="title">Reset Password </p>
            <p class="message">Reset Your Password</p>
                
                <label>
                    <input required="" placeholder="" type="password" class="input" name="password" id="password">
                    <span>Enter New Password</span>
                </label>
                <label>
                    <input required="" placeholder="" type="password" class="input" name="confirmpassword">
                    <span>Confirm New Password</span>
                    <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
                </label>
            
            <button type="submit" class="submit">Reset Password</button>
            <p class="signin">Already Have An Account?<a href="Emplogin.php">Sign in!</a> </p>
        </form>
        <script>
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

//Form check
            document.addEventListener("DOMContentLoaded", function () {
            const form = document.querySelector(".form");
            const passwordInput = document.querySelector('input[name="password"]');
            const confirmPasswordInput = document.querySelector('input[name="confirmpassword"]');
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
                    Swal.fire('Passwords do not match. Please try again.');
                    }
                });
                });
        </script>
    </body>
</html>