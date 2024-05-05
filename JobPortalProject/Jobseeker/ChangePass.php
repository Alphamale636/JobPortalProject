<?php
include('../config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPassword = $_POST['oldpassword'];
    $newPassword = $_POST['newpassword'];

    $username = $_SESSION['username'];
    $query = "SELECT `password` FROM jobseekerprofiles WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $storedPassword);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Verify the old password
    if (password_verify($oldPassword, $storedPassword)) {
        // Old password is correct, update the password with the new one
        $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        
        $updateQuery = "UPDATE jobseekerprofiles SET password = ? WHERE username = ?";
        $updateStmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($updateStmt, "ss", $hashedNewPassword, $username);
        mysqli_stmt_execute($updateStmt);


        header('Location: ProfileSection.php?passwordupdated=1');
        exit();
    } else {
        header('Location: ChangePass.php?incorrectoldpassword=1');
        $error = "Incorrect old password. Please try again.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="css/ChangePass.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    </head>
    <title>Reset Password</title>
    <body>
        <form class="form" method="POST" enctype="multipart/form-data" id="myForm">
            <p class="title">Change Password </p>
            <p class="message">Change Your Password</p>

            <label>
                    <input required="" placeholder="" type="password" class="input" name="oldpassword">
                    <span>Enter Old Password</span>
                </label>
                
                <label>
                    <input required="" placeholder="" type="password" class="input" name="newpassword" id="password">
                    <span>Enter New Password</span>
                </label>
                <label>
                    <input required="" placeholder="" type="password" class="input" name="confirmpassword">
                    <span>Confirm New Password</span>
                </label>
            
            <button type="submit" class="submit">Reset Password</button>
            <p class="signin"><a href="ProfileSection.php">&nbsp;Back To Profile</a> </p>
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
            const passwordInput = document.querySelector('input[name="newpassword"]');
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

// SweetAlert2
    if (window.location.search.indexOf('incorrectoldpassword') > -1) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Old Password Is Incorrect! Please Try Again!',
            timer: 1500
        });
        setTimeout(function () {
            window.location.href = 'ChangePass.php'; // Redirect to the final target page
        }, 1600); // Adjust the delay time as needed
}
        </script>
    </body>
</html>