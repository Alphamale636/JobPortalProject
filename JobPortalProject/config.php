<?php
// Database connection
$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "jobportal";

$conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Define the base path to the uploads directory
define('UPLOADS_PATH', 'uploads/');

// Start the session
session_start();
?>