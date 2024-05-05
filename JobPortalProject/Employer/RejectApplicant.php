<?php
include('../config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the application ID from the POST request
    $applicationId = $_POST['application_id'];

    

    $queryApplicationDetails = "SELECT job_id, jobseeker_id FROM applications WHERE application_id = ?";
    $stmtApplicationDetails = mysqli_prepare($conn, $queryApplicationDetails);
    mysqli_stmt_bind_param($stmtApplicationDetails, "i", $applicationId);
    mysqli_stmt_execute($stmtApplicationDetails);
    mysqli_stmt_bind_result($stmtApplicationDetails, $jobId, $jobseekerId);
    mysqli_stmt_fetch($stmtApplicationDetails);
    mysqli_stmt_close($stmtApplicationDetails);

    $queryJobSeekerEmail = "SELECT email FROM jobseekerprofiles WHERE user_id = ?";
    $stmtJobSeekerEmail = mysqli_prepare($conn, $queryJobSeekerEmail);
    mysqli_stmt_bind_param($stmtJobSeekerEmail, "i", $jobseekerId);
    mysqli_stmt_execute($stmtJobSeekerEmail);
    mysqli_stmt_bind_result($stmtJobSeekerEmail, $seekerEmail);
    mysqli_stmt_fetch($stmtJobSeekerEmail);
    mysqli_stmt_close($stmtJobSeekerEmail);

            $queryJobDetails = "SELECT job.jobtitle, emp.company_name, emp.email, emp.contact_number
                        FROM joblistings AS job
                        INNER JOIN employerprofiles AS emp ON job.employer_id = emp.employer_id
                        WHERE job.job_id = ?";
        $stmtJobDetails = mysqli_prepare($conn, $queryJobDetails);
        mysqli_stmt_bind_param($stmtJobDetails, "i", $jobId);
        mysqli_stmt_execute($stmtJobDetails);
        mysqli_stmt_bind_result($stmtJobDetails, $jobTitle, $employerName, $employerEmail, $contactNumber);
        mysqli_stmt_fetch($stmtJobDetails);
        mysqli_stmt_close($stmtJobDetails);

    

    
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
            $mail->Subject = 'Sorry! Your Application Has Been Rejected';
            $mail->Body = "Sorry! We regret to inform you that your application for the job titled '$jobTitle' has been rejected.<br>";
            $mail->Body .= 'Company Name: ' . $employerName . '<br>';
            $mail->Body .= 'Company Contact: ' . $contactNumber . '<br>';
            $mail->Body .= 'Company Email: ' . $employerEmail . '<br>';
            $mail->Body .= 'Stay tuned for further updates.';
            if ($mail->send()) {

                        // Update the status field to 1 for the specified application ID
            $query = "UPDATE applications SET status = 2 WHERE application_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $applicationId);
            mysqli_stmt_execute($stmt);


            $notificationMessage = "Your Application For '$jobTitle' Has Been Rejected.";
            $queryInsertNotification = "INSERT INTO notifications (jobseeker_id, message) VALUES (?, ?)";
            $stmtInsertNotification = $conn->prepare($queryInsertNotification);
            $stmtInsertNotification->bind_param("is", $jobseekerId, $notificationMessage);
            $stmtInsertNotification->execute();
    
       
        header('Location: Applicants.php?applicantrejected=1');
        exit();
    } else {
        // Handle the case where the update fails
        header('Location: Applicants.php?selectionfailed=1');
    }
}

    mysqli_stmt_close($stmt);

?>
