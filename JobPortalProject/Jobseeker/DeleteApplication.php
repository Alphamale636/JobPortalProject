<?php
include('../config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve first name and last name from the form
    $applicationId = $_POST['application_id'];


    $applicationId = $conn->real_escape_string($applicationId);


    // Query to delete the application seeker based on first name and last name
    $query = "DELETE FROM applications WHERE application_id = '$applicationId'";

    if ($conn->query($query) === TRUE) {
        header('Location: Applications.php?applicationdeleted=1');
    } else {
        echo "Error deleting application: " . $conn->error;
    }

    $conn->close();
} 
?>