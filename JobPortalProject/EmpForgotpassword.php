
<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions


// Database connection code
$servername ="localhost";
$username = "root";
$password = "";
$dbname = "jobportal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
// Get the user's input (username)
$username = $_POST['username'];

// Check if the username exists in the database
$sql = "SELECT `email` FROM employerprofiles WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
  // User found, fetch their email
  $stmt->bind_result($email);
  $stmt->fetch();

  // Generate a unique token for the password reset link
  $token = bin2hex(random_bytes(32));

  // Store the token in the database (associated with the user)
  $sql = "UPDATE employerprofiles SET reset_token = ? WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $token, $username);
  $stmt->execute();

  // Send the password reset email
  $mail = new PHPMailer(true);
  // Configure the email settings (SMTP, sender, recipient, subject, message)
  
  // ...
                                                              //Enable verbose debug output
  $mail->isSMTP();                                            //Send using SMTP
  $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
  $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
  $mail->Username   = 'recruitportal12@gmail.com';                     //SMTP username
  $mail->Password   = 'glpmyxqvjjasabsi';                               //SMTP password
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
  $mail->Port       = 465;                     
  $mail->setFrom('recruitportal12@gmail.com', 'Recruit');
  $mail->AddAddress($email);

  $mail->isHTML(true);
  $mail->Subject = "Password Reset Request";
  $mail->Body = "Click the following link to reset your password: 
                 <a href='localhost/JobPortalMiniProject/PassReset.php?token=$token'>Reset Password</a>";
 

  if ($mail->Send()) {
    header('Location: Emplogin.php?emailsent=1');
  } else {
    echo '<script>alert("Email could not be sent. Please try again later.");</script>';
    echo '<script>window.location.href = "EmpForgotpassword.php";</script>';
  }
} else {
  header('Location: EmpForgotpassword.php?username=1');
}
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="ForgotPassword.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    </head>
    <title>Forgot Password</title>
    <body>
        <form class="form" method="POST" enctype="multipart/form-data" id="myForm">
            <p class="title">Forgot Password </p>
            <p class="message">Enter Your Username </p>
                
                <label>
                    <input required="" placeholder="" type="text" class="input" name="username">
                    <span>Username</span>
                </label>
            
            <button class="submit">Submit</button>
            <p class="signin">Already Have An Account?<a href="Emplogin.php">Sign in!</a> </p>
        </form>
        <script>
            // SweetAlert2
    if (window.location.search.indexOf('username') > -1) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Invalid Username! Please Try Again!',
            timer: 1500
        });
        setTimeout(function () {
            window.location.href = 'EmpForgotpassword.php'; // Redirect to the final target page
        }, 1600); // Adjust the delay time as needed
}
          </script>
    </body>
</html>