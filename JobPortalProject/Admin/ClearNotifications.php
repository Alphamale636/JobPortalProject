<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['admin_id'])) {
        // Get the admin_id from the POST data
        $admin_id = $_POST['admin_id'];

        // Perform a database query to delete all notifications for the specified admin_id
        $query = "DELETE FROM notifications WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $admin_id);

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
