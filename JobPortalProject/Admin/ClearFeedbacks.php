<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Perform a database query to delete all feedbacks
    $query = "DELETE FROM feedbacks";

    if ($conn->query($query) === TRUE) {
        header('Location: Feedback.php?feedbackscleared=1');
    } else {
        echo "Error deleting feedbacks: " . $conn->error;
    }

    $conn->close();
}
?>
