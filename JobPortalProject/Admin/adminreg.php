<html>
    <head>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    </head>
    <title> Login </title>
    <body>
        <div class="login-box">
    <p>Login</p>
    <form method="post" action="adminreg.php" id="myForm">
      <div class="user-box">
        <input required="" name="username" type="text">
        <label>Username</label>
      </div>
      <div class="user-box">
        <input required="" name="password" type="password">
        <label>Password</label>
      </div>
      <label>
                    <input required="" placeholder="" type="text" class="input" name="fullname">
                    <span>Full Name</span>
                </label>
      <label>
      <span>Links</span>
      <textarea class="input" name="links" ></textarea>
                    
                </label>
              
                <label>
                    <input required="" placeholder="" type="text" class="input" name="officeaddress">
                    <span>officeaddress</span>
                </label>
                <label>
                
                <textarea class="input" name="aboutme"></textarea>
                    
                </label>
   
              
            
            <label>
                <input required="" placeholder="" type="text" class="input" name="contact-number">
                <span>Contact-number</span>
            </label> 
            
                    
            <label>
                <input required="" placeholder="" type="email" class="input" name="email">
                <span>Email</span>
            </label> 
                  
      <a href="#" id="submitLink">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        Submit
      </a>
    </form>
    <p class="signup">Don't have an account? <a href="" class="a2">Sign up!</a></p>
  </div>
  <script>
    //formsubmit
    document.getElementById('submitLink').addEventListener('click', function() {
      document.getElementById('myForm').submit();
    });
  </script>
  </body>
  </html>

  <?php
session_start();

$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "jobportal";

$conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedUsername = $_POST['username'];
    $submittedPassword = $_POST['password'];
    $fullname = $_POST['fullname'];
    $contact = $_POST['contact-number'];
    $email = $_POST['email'];
    $aboutme = $_POST['aboutme'];
    $links = $_POST['links'];
    $address = $_POST['officeaddress'];

    $conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }
    $hashedPassword = password_hash($submittedPassword, PASSWORD_BCRYPT);
    
    $query = "INSERT INTO `adminprofiles` (`username`, `password`, `fullname`, `contact_number`, `email`, `about_me`, `links`, `office_address`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

if ($stmt = mysqli_prepare($conn, $query)) {
    // Bind the parameters and execute the query
    mysqli_stmt_bind_param($stmt, "ssssssss", $submittedUsername, $hashedPassword, $fullname, $contact, $email, $aboutme, $links, $address);

    if (mysqli_stmt_execute($stmt)) {
        // Data inserted successfully
        echo "Data inserted successfully!";
    } else {
        // Failed to insert data
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    // Database query error
    echo "Database query error. Please try again later.";
    }
}
  mysqli_close($conn);
?>