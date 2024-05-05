<?php
// Include your database connection code
include('../config.php');


if (isset($_GET['selectedDate'])) {
    $selectedDate = $_GET['selectedDate'];

    

    // Initialize report content
    $reportContent = "Report for Date: $selectedDate\n\n";

    // Function to append data to the report
    function appendToReport(&$content, $title, $data) {
        $content .= "$title\n";
        foreach ($data as $item) {
            foreach ($item as $key => $value) {
                $content .= "$key: $value\n";
            }
            $content .= "\n";
        }
    }
                    // Fetch jobseeker data based on the selected date
                    $jobseekerQuery = "SELECT * FROM jobseekerprofiles WHERE joineddate = ?";
                    $jobseekerStmt = mysqli_prepare($conn, $jobseekerQuery);
                    mysqli_stmt_bind_param($jobseekerStmt, "s", $selectedDate);
                    mysqli_stmt_execute($jobseekerStmt);
                    $jobseekerResult = mysqli_stmt_get_result($jobseekerStmt);
                    $jobseekerData = mysqli_fetch_all($jobseekerResult, MYSQLI_ASSOC);
                    mysqli_stmt_close($jobseekerStmt);

                    // Fetch employer data based on the selected date
                    $employerQuery = "SELECT * FROM employerprofiles WHERE joineddate = ?";
                    $employerStmt = mysqli_prepare($conn, $employerQuery);
                    mysqli_stmt_bind_param($employerStmt, "s", $selectedDate);
                    mysqli_stmt_execute($employerStmt);
                    $employerResult = mysqli_stmt_get_result($employerStmt);
                    $employerData = mysqli_fetch_all($employerResult, MYSQLI_ASSOC);
                    mysqli_stmt_close($employerStmt);

                    // Fetch job listings data based on the selected date
                    $jobListingsQuery = "SELECT * FROM joblistings WHERE posteddate = ?";
                    $jobListingsStmt = mysqli_prepare($conn, $jobListingsQuery);
                    mysqli_stmt_bind_param($jobListingsStmt, "s", $selectedDate);
                    mysqli_stmt_execute($jobListingsStmt);
                    $jobListingsResult = mysqli_stmt_get_result($jobListingsStmt);
                    $jobListingsData = mysqli_fetch_all($jobListingsResult, MYSQLI_ASSOC);
                    mysqli_stmt_close($jobListingsStmt);

                    if (empty($jobseekerData) && empty($employerData) && empty($jobListingsData)) {
                        // No data found for the selected date
                        echo "No data found for the selected date.";
                    } else {
                    // Append data to the report
                    appendToReport($reportContent, "JobSeeker Data", $jobseekerData);
                    appendToReport($reportContent, "Employer Data", $employerData);
                    appendToReport($reportContent, "Job Listings Data", $jobListingsData);

                    // Generate a unique report file name
                    $reportFileName = "report_" . date("Ymd_His") . ".txt";

                    // Save the report as a text file
                    file_put_contents($reportFileName, $reportContent);
                    
                    // Output the report for download
                    header('Content-Type: text/plain');
                    header('Content-Disposition: attachment; filename="' . $reportFileName . '"');
                    readfile($reportFileName);

                    // Clean up: delete the temporary report file
                    unlink($reportFileName);
                    }
                }
                    ?>
