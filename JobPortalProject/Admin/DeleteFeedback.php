<?php
include('../config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve first name and last name from the form
    $feedbackId = $_POST['feedback_id'];


    $feedbackId = $conn->real_escape_string($feedbackId);


    // Query to delete the job seeker based on first name and last name
    $query = "DELETE FROM feedbacks WHERE feedback_id = '$feedbackId'";

    if ($conn->query($query) === TRUE) {
        header('Location: Feedback.php?feedbackdeleted=1');
    } else {
        echo "Error deleting feedback: " . $conn->error;
    }

    $conn->close();
} 
?>