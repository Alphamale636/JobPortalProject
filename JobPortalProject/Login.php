<html>
    <head>
        <link rel="stylesheet" type="text/css" href="Login.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    </head>
    <title> Login </title>
    <body>
      <div class="logobox">
        <div class="logo"><img src="images/RECRUITLOGOWITHNAME.png" alt="NOT FOUND"></div>
        </div>
        <div class="login-box">
    <div class="contents"><p>Login</p>
    <form method="post" action="Login.php" id="myForm">
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
    <p class="signup">Don't have an account? <a href="Jobseeker/SeekerRegister.php" class="a2">Sign up!</a></p>
    <p class="signup">Employer? <a href="Emplogin.php" class="a2">Sign in!</a></p>
    <p class="signup">Forgot Password? <a href="SeekerForgotpassword.php" class="a2">Here!</a></p>
  </div>
  </div>
  
  <script>
    //formsubmit
    document.getElementById('submitLink').addEventListener('click', function() {
      event.preventDefault();
      document.getElementById('myForm').submit();
    });

  //sweetalert    
    if (window.location.search.indexOf('Jobseeker') > -1) {
                Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Account Created Successfully',
                showConfirmButton: false,
                timer: 1500
            });
            setTimeout(function () {
                window.location.href = 'Login.php'; // Redirect to the final target page
            }, 1600); // Adjust the delay time as needed
        }



    // SweetAlert2
    if (window.location.search.indexOf('invalid') > -1) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Invalid Credentials! Please Try Again!',
        });
        setTimeout(function () {
            window.location.href = 'Login.php'; // Redirect to the final target page
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
                window.location.href = 'Login.php'; // Redirect to the final target page
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
                window.location.href = 'Login.php'; // Redirect to the final target page
            }, 3200); // Adjust the delay time as needed
        }
         // SweetAlert2
    if (window.location.search.indexOf('reseterror') > -1) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Error Resetting Password! Please Try Again!',
        });
        setTimeout(function () {
            window.location.href = 'Login.php'; // Redirect to the final target page
        }, 1700); // Adjust the delay time as needed
        
       
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

class FormRateLimiter {
    private $sessionKey;
    private $limit;
    private $window;

    public function __construct($sessionKey, $limit, $window) {
        $this->sessionKey = $sessionKey;
        $this->limit = $limit;
        $this->window = $window;
    }

    public function allowSubmission() {
        $submissions = isset($_SESSION[$this->sessionKey]) ? $_SESSION[$this->sessionKey] : [];
        
        // Clean up old submissions
        $this->cleanUpOldSubmissions($submissions);

        // Count submissions within the window
        $count = count($submissions);

        if ($count < $this->limit) {
            // Add the current submission timestamp
            $submissions[] = time();
            $_SESSION[$this->sessionKey] = $submissions;
            return true; // Submission allowed
        } else {
            return false; // Submission denied
        }
    }

    private function cleanUpOldSubmissions(&$submissions) {
        $currentTime = time();
        foreach ($submissions as $key => $submissionTime) {
            if ($currentTime - $submissionTime > $this->window) {
                unset($submissions[$key]);
            }
        }
    }
}

    // Define your rate limiting parameters
    $rateLimiter = new FormRateLimiter('form_submissions', 3, 60); 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!$rateLimiter->allowSubmission()) {
        // Submission rate limit exceeded, handle accordingly
       $message = "Submission rate limit exceeded. Please try again later after a sometime";
       echo "<script type='text/javascript'>alert('$message');</script>";
        exit(); // Stop further processing
    	}		
    $submittedUsername = ($_POST['username']);
    $submittedPassword = ($_POST['password']);
    

    // Query the admin table
    $queryAdmin = "SELECT `username`, `password` FROM `adminprofiles` WHERE `username` = ?";
    
    if ($stmtAdmin = mysqli_prepare($conn, $queryAdmin)) {
        mysqli_stmt_bind_param($stmtAdmin, "s", $submittedUsername);
        mysqli_stmt_execute($stmtAdmin);
        mysqli_stmt_bind_result($stmtAdmin, $username, $hashedPassword);
        mysqli_stmt_fetch($stmtAdmin);



        if (password_verify($submittedPassword, $hashedPassword)) {
            $_SESSION['username'] = $username;

            
            $currentTimestamp = date("Y-m-d H:i:s");
            mysqli_stmt_close($stmtAdmin);

            $updateQuery = "UPDATE `adminprofiles` SET `last_login` = ? WHERE `username` = ?";
            if ($updateStmt = mysqli_prepare($conn, $updateQuery)) {
                mysqli_stmt_bind_param($updateStmt, "ss", $currentTimestamp, $username);
                mysqli_stmt_execute($updateStmt);
            }

            mysqli_stmt_close($updateStmt);

            header('Location: Admin/Dashboard.php');
            exit();
        }

        mysqli_stmt_close($stmtAdmin);
    } else {
        $error = "Database query error for admin table. Please try again later.";
    }

    // If no result found in admin table, query the jobseekerprofile table
    $queryJobSeeker = "SELECT `username`, `password` FROM `jobseekerprofiles` WHERE `username` = ?";
    
    if ($stmtJobSeeker = mysqli_prepare($conn, $queryJobSeeker)) {
        mysqli_stmt_bind_param($stmtJobSeeker, "s", $submittedUsername);
        mysqli_stmt_execute($stmtJobSeeker);
        mysqli_stmt_bind_result($stmtJobSeeker, $username, $hashedPassword);
        mysqli_stmt_fetch($stmtJobSeeker);

        

        if (password_verify($submittedPassword, $hashedPassword)) {
            $_SESSION['username'] = $username;

            $currentTimestamp = date("Y-m-d H:i:s");
            mysqli_stmt_close($stmtJobSeeker);

            $updateQuery = "UPDATE `jobseekerprofiles` SET `last_login` = ? WHERE `username` = ?";
            if ($updateStmt = mysqli_prepare($conn, $updateQuery)) {
                mysqli_stmt_bind_param($updateStmt, "ss", $currentTimestamp, $username);
                mysqli_stmt_execute($updateStmt);
            }

            mysqli_stmt_close($updateStmt);

            header('Location: Jobseeker/DashboardSection.php'); // Redirect to the job seeker dashboard or another page
            exit();
        }
        else {
          // JavaScript alert for incorrect login credentials
          header('Location: Login.php?invalid=1');
      }
    

        
        
    } else {
        $error = "Database query error for jobseekerprofile table. Please try again later.";
        
    }
    
}

mysqli_close($conn);
?>
