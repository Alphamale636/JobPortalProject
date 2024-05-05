<?php
include('../config.php'); // Include your database configuration

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the job_id and jobseeker_id from the form data
    $jobId = $_POST['job_id'];
    $seekerUsername = $_POST['jobseeker_id'];

    // Assuming you have a database connection established as $conn
    $query1 = "SELECT employer_id, jobtitle FROM joblistings WHERE job_id = ?";
    $stmt1 = mysqli_prepare($conn, $query1);
    mysqli_stmt_bind_param($stmt1, "i", $jobId);
    mysqli_stmt_execute($stmt1);
    mysqli_stmt_bind_result($stmt1, $employerId, $jobtitle);
    mysqli_stmt_fetch($stmt1);
    mysqli_stmt_close($stmt1);

    $query2 = "SELECT first_name, last_name FROM jobseekerprofiles WHERE user_id = ?";
    $stmt2 = mysqli_prepare($conn, $query2);
    mysqli_stmt_bind_param($stmt2, "i", $seekerUsername);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_bind_result($stmt2, $firstName, $lastName);
    mysqli_stmt_fetch($stmt2);
    mysqli_stmt_close($stmt2);


    // Check if the application already exists
    $query = "SELECT * FROM applications WHERE job_id = ? AND jobseeker_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $jobId, $seekerUsername);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    // If a matching application is found, display an error or take appropriate action
    if (mysqli_stmt_num_rows($stmt) > 0) {
        // Application already exists, display an error message or take action
        header('Location: JobListings.php?alreadyexist=1');
    } else {
        // Insert the application into the applications table with a pending status
        $applicationDate = date("Y-m-d"); // Get the current date
        $status = 0; // 0 represents pending

        $sql = "INSERT INTO applications (`job_id`, `jobseeker_id`, `application_date`, `status`) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $jobId, $seekerUsername, $applicationDate, $status);



        if (mysqli_stmt_execute($stmt)) {
            // Application inserted successfully
            $notificationMessage = "Application Submitted For : '$jobtitle' by '$firstName $lastName'";
            $queryInsertNotification = "INSERT INTO notifications (employer_id, message) VALUES (?, ?)";
            $stmtInsertNotification = $conn->prepare($queryInsertNotification);
            $stmtInsertNotification->bind_param("is", $employerId, $notificationMessage);
            $stmtInsertNotification->execute();
    

            header('Location: JobListings.php?submitted=1');
        } else {
            // Error inserting application
            echo "Error submitting the application. Please try again later.";
        }
    }
}


// Close the database connection if needed
mysqli_close($conn);
?>
