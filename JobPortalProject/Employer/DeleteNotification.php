<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['notification_id'])) {
        // Get the notification_id from the form submission
        $notification_id = $_POST['notification_id'];

        // Perform a database query to delete the notification with the specified notification_id
        $query = "DELETE FROM notifications WHERE notification_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $notification_id);

        if (mysqli_stmt_execute($stmt)) {
            // Deletion was successful
            $response = array("success" => true);
        } else {
            $response = array("success" => false, "error" => "Error deleting notification: " . mysqli_error($conn));
        }

        // Respond with JSON data
        header("Content-Type: application/json");
        echo json_encode($response);
        exit;
    }
}
?>
