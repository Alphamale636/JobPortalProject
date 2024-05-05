<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['jobseeker_id'])) {
        // Get the admin_id from the POST data
        $jobseeker_id = $_POST['jobseeker_id'];

        // Perform a database query to delete all notifications for the specified jobseeker_id
        $query = "DELETE FROM notifications WHERE jobseeker_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $jobseeker_id);

        if (mysqli_stmt_execute($stmt)) {
            // Deletion was successful
            $response = array("success" => true);
        } else {
            $response = array("success" => false, "error" => "Error clearing notifications: " . mysqli_error($conn));
        }

        // Respond with JSON data
        header("Content-Type: application/json");
        echo json_encode($response);
        exit;
    }
}
?>
