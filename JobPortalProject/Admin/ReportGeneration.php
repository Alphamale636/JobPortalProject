<?php
include('../config.php');
// Fetch the profile picture URL from the database
        $adminUsername = $_SESSION['username'];
        $query = "SELECT profile_picture FROM adminprofiles WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $adminUsername);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $profilePictureURL);

        // Fetch the result and close the statement
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Report</title>
        <link rel="stylesheet" type="text/css" href="css/ReportGeneration.css">
        <link rel="stylesheet" type="text/css" href="css/Admindash.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" type="text/css" href="css/logout.css">
        </head>
        <body>
            <div class="container">
                <div class="navigation">
                        <div class="logo">
                            <img src="../images/RECRUIT_LOGO_2.png" alt="">
                            <h1>Recruit</h1>
                        </div>
                        <ul>
                            <li>
                                <a href="Dashboard.php"> 
                        <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
                        <span class="nav-item">Dashboard</span>
                        </a>
                        </li>
                            <li>
                                <a href="Profile.php"> 
                        <span class="icon"><ion-icon name="person-outline"></ion-icon></span>
                        <span class="nav-item">Profile</span>
                        </a>
                        </li>
                        <li>
                            <a href="Jobseekermanage.php">
                            <span class="icon"> <ion-icon name="people-outline"></ion-icon> </i></span>
                        <span class="nav-item">Manage Job Seekers</span>
                        </a>
                        </li>
                        <li>
                            <a href="EmployerManage.php">
                            <span class="icon"><ion-icon name="people"></ion-icon> </span>
                        <span class="nav-item">Manage Employers</span>
                        </a>
                        </li>
                        <li>
                            <a href="JobListingsManage.php">
                            <span class="icon"><ion-icon name="list-outline"></ion-icon> </span>
                        <span class="nav-item">Manage Job Listings</span>
                        </a>
                        </li>
                       
                        <li>
                            <a href="Notifications.php">
                            <span class="icon"><ion-icon name="notifications-outline"></ion-icon> </span>
                        <span class="nav-item">Notifications</span>
                        </a>
                        </li>
                        <li>
                            <a href="Feedback.php"><span class="icon"><ion-icon name="chatbubble-outline"></ion-icon></span>
                        <span class="nav-item">Feedbacks</span>
                        </a>
                        </li>
                        <li class="active1">
                            <a href="ReportGeneration.php" class="active2"><span class="icon"><ion-icon name="document-text-outline"></ion-icon></span>
                        <span class="nav-item">Report Generation</span>
                        </a>
                        </li>
                    
                    
                    
                        <li>
                            <a href=# class="logout" onclick="ShowCustomConfirm()">
                            <span class="icon"><ion-icon name="log-out-outline"></ion-icon> </span>
                        <span class="nav-item">Logout</span>
                        </a>
                        </li>
                        </ul>
                    </div>
                    
                
                <div class="main">
                    <div class="topbar">
                        <div class="toggle">
                            <ion-icon name="menu-outline"></ion-icon>
                        </div>
                        <div class="topname">
                            <h1>Report Generation</h1>
                        </div>
                        <!--userImg-->  
                        <?php echo '<div class="user"><img src="' . $profilePictureURL . '" onerror="this.src=\'../images/user.png\';" class="userimg"></div>';?>  
                    </div>
                    <div class="details">
                        <div class="Report">
                            <div class="ReportHeader">
                                <h2><ion-icon name="document-text-outline"></ion-icon> Report</h2>
                            <div class="textheader">
                                <div class="text">Generate Report</div>
                            </div>
                            
                            
                        <div class="Upload">
                            <div class="UploadCard">
                                <div class="card">
                            <form action="GenerateReport.php" method="post">
                                    <div class="datepk"><h4>Select Date: </h4><input type="text" id="datepicker"></div>
                                    <div class="Uploadbtn" id="generateReportBtn"><a  href="#"><ion-icon name="document-text-outline"></ion-icon>&nbsp Generate Report</a></div>
                               </div>
   
                               <div class="card1" id="card1">
                               <div id="TextContent" class="TextContent">Empty</div>
                               <div class="DownloadReportBtn" style="display:none; width:250px;" id="download"><a  id="downloadReportBtn"><ion-icon name="cloud-download-outline"></ion-icon>&nbsp Download Report</a></div>
                                
                                </div>
                            </form>
                            
                            </div>
                     </div>
                </div>
            </div>
         </div>
         <div id="overlay" class="overlay"></div>
         <div id="custom-confirm" class="model" style="display:none">
             <div class="model-content">
                 <div class="confirmationtext">
                 <p>Are you sure you want to logout?</p>
                 </div>
                 <div class="buttoncontainer">
                 <button id="yes-button">Yes</button>
                 <button id="no-button">No</button>
                 </div>
             </div>
         </div>

                </div>
            </div>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
        <script src="/Jobseeker/js/my_chart.js"></script>
        <script src="js/logout.js"></script>
        <script src="js/highlightnavigation.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        
        <script>
            //DatePicker
                flatpickr("#datepicker", {});
           
            //MenuToggle
            let toggle = document.querySelector('.toggle');
            let navigation = document.querySelector('.navigation');
            let main = document.querySelector('.main');

            toggle.onclick = function(){
                navigation.classList.toggle('active');
                main.classList.toggle('active');
            }
           




function generateReport() {
    // Get the selected date from the date picker
    const selectedDate = document.getElementById("datepicker").value;

    // Make a GET request to the PHP script to generate the report
    fetch(`GenerateReport.php?selectedDate=${selectedDate}`)
        .then(response => response.text())
        .then(reportContent => {
            const card1 = document.querySelector('.card1');
            if (reportContent.trim() === 'No data found for the selected date.') {
                // Handle the case where no data was found for the selected date
                const textContentElement = document.getElementById("TextContent");
                textContentElement.textContent = "No Data Found For The Selected Date";

                // Hide the "Download Report" button
                const downloadReportBtn = document.getElementById("downloadReportBtn");
                downloadReportBtn.style.display = 'none'
                // Modify card1 styling
                const card1 = document.querySelector('.card1');
                card1.style.padding = '40px';
                card1.style.color = '#152935';
            } else {
                const textContentElement = document.getElementById("TextContent");
                textContentElement.textContent = "Report Generated Successfully";
                textContentElement.style.display = "none";


                 // Create a downloadable link
                const blob = new Blob([reportContent], { type: 'text/plain' });
                const url = URL.createObjectURL(blob);

                const download1 = document.getElementById("download");
                download1.style.display = "block";

                const downloadReportBtn = document.getElementById("downloadReportBtn");
                downloadReportBtn.href = url;
                downloadReportBtn.download = `report_${selectedDate}.txt`;
                downloadReportBtn.style.position = 'relative';
                downloadReportBtn.style.top = "410px";
                downloadReportBtn.style.left = "20px";
                  // Modify card1 styling
                  const pre = document.createElement('pre');
            pre.style.fontSize = '14px'; 
            pre.style.width = '700px';
            pre.style.height = '95%';
            pre.style.position = 'relative';
            pre.style.border = '1px solid #999';
            pre.style.borderRadius = '20px';
            pre.style.top = '-30px';
            pre.style.padding = '10px';
            pre.style.left = '2px';
            pre.style.margin = '10px';
            pre.style.overflowY = 'scroll';
            pre.style.overflowX= 'hidden';
            pre.style.zIndex = '100';
            pre.textContent = reportContent;

            // Append the pre element to card1

            card1.appendChild(pre);
            const children = card1.children;
        if (children.length > 2) {
            for (let i = 2; i < children.length; i++) {
                card1.removeChild(children[3]);
            }
        }
            

            }
        })
        .catch(error => {
            console.error("Error generating report: ", error);
        });
}




 
const generateReportBtn = document.getElementById('generateReportBtn');
generateReportBtn.addEventListener('click',  function () {
    
    generateReport();
});

            </script>
        </body>
</html>