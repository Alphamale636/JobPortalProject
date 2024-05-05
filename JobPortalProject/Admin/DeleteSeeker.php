<?php
include('../config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve first name and last name from the form
    $firstname = $_POST['delete_firstname'];
    $lastname = $_POST['delete_lastname'];

    $firstname = $conn->real_escape_string($firstname);
    $lastname = $conn->real_escape_string($lastname);

    // Query to delete the job seeker based on first name and last name
    $query = "DELETE FROM jobseekerprofiles WHERE first_name = '$firstname' AND last_name = '$lastname'";

    if ($conn->query($query) === TRUE) {
        header('Location: Jobseekermanage.php?accountdeleted=1');
    } else {
        echo "Error deleting job seeker: " . $conn->error;
    }

    $conn->close();
} 
?>