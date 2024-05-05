<?php
include('../config.php');


// Check if a user is logged in (you should have a session variable for this)
if (isset($_SESSION['username'])) {
   
    if (isset($_FILES['resume'])) {
        // Check the file type (in this case, accept PDF and TXT files)
        $allowedExtensions = array("pdf", "txt");
        $fileExtension = pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION);

        if (in_array(strtolower($fileExtension), $allowedExtensions)) {
            $username = $_SESSION['username'];
            $sqlSelect = "SELECT user_id FROM jobseekerprofiles WHERE username = '$username'";
            $result = $conn->query($sqlSelect);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_id = $row['user_id'];

                // Update the user's resume based on the user_id
                $file = $_FILES['resume']['tmp_name'];
                $resumeData = file_get_contents($file);
                $escapedResumeData = mysqli_real_escape_string($conn, $resumeData); // Convert line breaks
                $sqlUpdate = "UPDATE jobseekerprofiles SET resume = '$escapedResumeData' WHERE user_id = $user_id";

               
                if ($conn->query($sqlUpdate) === TRUE) {
                    $response['success'] = true;
                    $response['message'] = "Resume updated successfully.";
                } else {
                    $response['success'] = false;
                    $response['message'] = "Error updating the resume: " . $conn->error;
                }
            } else {
                $response['user'] = false;
                $response['message'] = "User not found.";
            }
        } else {
            $response['file'] = false;
            $response['message'] = "Invalid file type.";
        }
    } else {
        $response['notupload'] = false;
        $response['message'] = "No file was uploaded.";
    }

    $conn->close();
} else {
    $response['success'] = false;
    $response['message'] = "User not logged in.";
}

// Convert the response array to JSON and echo it
echo json_encode($response);
?>

