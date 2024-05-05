<?php
include('../config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve first name and last name from the form
    $jobId = $_POST['job_id'];


    $jobId = $conn->real_escape_string($jobId);


    // Query to delete the job seeker based on first name and last name
    $query = "DELETE FROM joblistings WHERE job_id = '$jobId'";

    if ($conn->query($query) === TRUE) {
        header('Location: JobListingsManage.php?jobdeleted=1');
    } else {
        echo "Error deleting job: " . $conn->error;
    }

    $conn->close();
} 
?>