<?php
include('../config.php');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';

// Assuming you've already retrieved these values from the form or session
$user_id = $_POST['user_id'];
$employerUsername = $_POST['employerUsername'];

$queryEmployerId = "SELECT employer_id FROM employerprofiles WHERE username = ?";
$stmtEmployerId = mysqli_prepare($conn, $queryEmployerId);
mysqli_stmt_bind_param($stmtEmployerId, "s", $employerUsername);
mysqli_stmt_execute($stmtEmployerId);
mysqli_stmt_bind_result($stmtEmployerId, $employerId);
mysqli_stmt_fetch($stmtEmployerId);
mysqli_stmt_close($stmtEmployerId);

// Check if the candidate was already selected
$queryCheckSelected = "SELECT status FROM applications WHERE jobseeker_id = ? AND job_id = 1";
$stmtCheckSelected = mysqli_prepare($conn, $queryCheckSelected);
mysqli_stmt_bind_param($stmtCheckSelected, "i", $user_id);
mysqli_stmt_execute($stmtCheckSelected);
mysqli_stmt_bind_result($stmtCheckSelected, $status);
mysqli_stmt_fetch($stmtCheckSelected);
mysqli_stmt_close($stmtCheckSelected);

if ($status == 1) {
    header('Location: CandidateListings.php?alreadyselected=1');
    exit; // Exit the script to prevent further execution
}

// Fetch employer's information
$queryEmployerInfo = "SELECT company_name, email, contact_number FROM employerprofiles WHERE username = ?";
$stmtEmployerInfo = $conn->prepare($queryEmployerInfo);
$stmtEmployerInfo->bind_param("s", $employerUsername);
$stmtEmployerInfo->execute();
$stmtEmployerInfo->bind_result($companyName, $employerEmail, $employerContact);
$stmtEmployerInfo->fetch();
$stmtEmployerInfo->close();

// Fetch job seeker's email
$querySeekerEmail = "SELECT email FROM jobseekerprofiles WHERE user_id = ?";
$stmtSeekerEmail = $conn->prepare($querySeekerEmail);
$stmtSeekerEmail->bind_param("i", $user_id);
$stmtSeekerEmail->execute();
$stmtSeekerEmail->bind_result($seekerEmail);
$stmtSeekerEmail->fetch();
$stmtSeekerEmail->close();

// Compose the email
$mail = new PHPMailer;

$mail->isSMTP();                                            
  $mail->Host       = 'smtp.gmail.com';                     
  $mail->SMTPAuth   = true;                                   
  $mail->Username   = 'recruitportal12@gmail.com';                     
  $mail->Password   = 'glpmyxqvjjasabsi';                     
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
  $mail->Port       = 465;                     
  $mail->setFrom('recruitportal12@gmail.com', 'Recruit');


$mail->addAddress($seekerEmail);

$mail->isHTML(true);
$mail->Subject = 'You have been selected by ' . $companyName;
$mail->Body = 'Congratulations! You have been selected by ' . $companyName . '.<br>';
$mail->Body .= 'Company Contact: ' . $employerContact . '<br>';
$mail->Body .= 'Company Email: ' . $employerEmail . '<br>';
$mail->Body .= 'Stay tuned for further updates.';

if (!$mail->send()) {
    header('Location: CandidateListings.php?failed=1');
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    // Insert data into the applications table with status 1 (selected)
   // Now you have the employer's ID, and you can insert it into the applications table
        $queryInsertApplication = "INSERT INTO applications (jobseeker_id, job_id, status, employer_id) VALUES (?, 1, 1, ?)";
        $stmtInsertApplication = mysqli_prepare($conn, $queryInsertApplication);
        mysqli_stmt_bind_param($stmtInsertApplication, "ii", $user_id, $employerId);
        
    if ($stmtInsertApplication->execute()) {
        // Notify the job seeker
        $notificationMessage = "You have been selected by $companyName. Please Check Your Email.";
        $queryInsertNotification = "INSERT INTO notifications (jobseeker_id, message) VALUES (?, ?)";
        $stmtInsertNotification = $conn->prepare($queryInsertNotification);
        $stmtInsertNotification->bind_param("is", $user_id, $notificationMessage);
        $stmtInsertNotification->execute();

        header('Location: CandidateListings.php?selected=1');
    } else {
        echo 'Failed to insert application data';
    }
}
// Close the database connection
$conn->close();
?>
