<html>
    <head>
        <link rel="stylesheet" type="text/css" href="emplogin.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    </head>
    <title> Login </title>
    <body>
      <div class="logobox">
        <div class="logo"><img src="images/RECRUITLOGOWITHNAME.png" alt="NOT FOUND"></div>
        </div>
        <div class="login-box">
    <div class="contents"><p>Employer Login</p>
    <form method="post" action="Emplogin.php" id="myForm">
      <div class="user-box">
        <input required="" name="username" type="text">
        <label>Username</label>
      </div>
      <div class="user-box">
        <input required="" name="password" type="password">
        <label>Password</label>
      </div>
      <a href="#" id="submitLink">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        Submit
      </a>
    </form>
    <p class="signup">Don't have an account? <a href="Employer/empregister.php" class="a2">Sign up!</a></p>
    <p class="signup">JobSeeker? <a href="Login.php" class="a2">Sign in!</a></p>
    <p class="signup">Forgot Password? <a href="EmpForgotpassword.php" class="a2">Here!</a></p>
  </div>
  </div>
  
  <script>
    //formsubmit
    document.getElementById('submitLink').addEventListener('click', function() {
      event.preventDefault();
      document.getElementById('myForm').submit();
    });

 // SweetAlert2
     if (window.location.search.indexOf('invalid') > -1) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Invalid Credentials! Please Try Again!',
        });
        setTimeout(function () {
            window.location.href = 'Emplogin.php'; // Redirect to the final target page
        }, 1700); // Adjust the delay time as needed
    }
    //sweetalert2
     if (window.location.search.indexOf('employer') > -1) {
                Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Account Created Successfully',
                showConfirmButton: false,
                timer: 1500
            });
            setTimeout(function () {
                window.location.href = 'Emplogin.php'; // Redirect to the final target page
            }, 1700); // Adjust the delay time as needed
        }
        // SweetAlert2
 if (window.location.search.indexOf('emailsent') > -1) {
                Swal.fire({
                icon: 'success',
                title: 'Email Sent Successfully, Please Check Your Inbox.',
                showConfirmButton: false,
                timer: 3000
            });
            setTimeout(function () {
                window.location.href = 'Emplogin.php'; // Redirect to the final target page
            }, 3200); // Adjust the delay time as needed
        }

        //sweetalert
        if (window.location.search.indexOf('passwordreset') > -1) {
                Swal.fire({
                icon: 'success',
                title: 'Password reset successful. You can now login with your new password.',
                showConfirmButton: false,
                timer: 3000
            });
            setTimeout(function () {
                window.location.href = 'Emplogin.php'; // Redirect to the final target page
            }, 3200); // Adjust the delay time as needed
        }
  </script>
  </body>
  </html>

  <?php
session_start();

$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "jobportal";

$conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedUsername = $_POST['username'];
    $submittedPassword = $_POST['password'];

    // Query the admin table
    $query = "SELECT `username`, `password` FROM `employerprofiles` WHERE `username` = ?";
    
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "s", $submittedUsername);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $username, $hashedPassword);
        mysqli_stmt_fetch($stmt);

        if (password_verify($submittedPassword, $hashedPassword)) {
            $_SESSION['username'] = $username;


            $currentTimestamp = date("Y-m-d H:i:s");
            mysqli_stmt_close($stmt);

            $updateQuery = "UPDATE `employerprofiles` SET `last_login` = ? WHERE `username` = ?";
            if ($updateStmt = mysqli_prepare($conn, $updateQuery)) {
                mysqli_stmt_bind_param($updateStmt, "ss", $currentTimestamp, $username);
                mysqli_stmt_execute($updateStmt);
            }

            mysqli_stmt_close($updateStmt);


            header('Location: Employer/Dashboard.php');
            exit();
        }
        else{
          header('Location: Emplogin.php?invalid=1');
        }

        mysqli_stmt_close($stmt);
    } else {
        $error = "Database query error for admin table. Please try again later.";
    }
   
}

mysqli_close($conn);
?>
