<?php
include('../config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve first name and last name from the form
    $employerId = $_POST['employer_id'];


    $employerId = $conn->real_escape_string($employerId);


    // Query to delete the job seeker based on first name and last name
    $query = "DELETE FROM employerprofiles WHERE employer_id = '$employerId'";

    if ($conn->query($query) === TRUE) {
        header('Location: EmployerManage.php?accountdeleted=1');
    } else {
        echo "Error deleting job seeker: " . $conn->error;
    }

    $conn->close();
} 
?>