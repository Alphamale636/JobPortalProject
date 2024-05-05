<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch the user_id using the session username
    $sessionUsername = $_SESSION['username'];
    $getUserIDQuery = "SELECT user_id FROM jobseekerprofiles WHERE username = ?";
    $stmt = $conn->prepare($getUserIDQuery);
    $stmt->bind_param("s", $sessionUsername);
    $stmt->execute();
    $stmt->bind_result($user_id);
    
    if ($stmt->fetch()) {
        // Use the fetched user_id in the DELETE query
        $stmt->close(); // Close the previous statement
        
        $deleteQuery = "DELETE FROM applications WHERE jobseeker_id = ? AND job_id <> 1";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $user_id);
        
        if ($stmt->execute()) {
            header('Location: Applications.php?allapplicationscleared=1');
        } else {
            echo "Error deleting feedbacks: " . $conn->error;
        }
    } else {
        echo "Error fetching user_id: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
