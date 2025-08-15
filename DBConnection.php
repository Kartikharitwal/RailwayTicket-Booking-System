<?php
// creating connection with db
$servername = "localhost";
$username = "root";
<<<<<<< HEAD
$password = ""; // Default password for root in XAMPP is empty
$dbname = "traindb"; // Match the database name you have in phpMyAdmin
=======
$password = "-0987654321`"; // Note: There's a backtick character at the end that might be a typo
$dbname = "railway_db"; // Updated database name
>>>>>>> 9cde57e9d4fe1346c087eb8f14242abaee368fb0

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
<<<<<<< HEAD
} 
?>
=======
} else {
    // Uncomment this line if you want to check if the connection is successful
    // echo "<script>alert('Connection Successful');</script>";
}
?>
>>>>>>> 9cde57e9d4fe1346c087eb8f14242abaee368fb0
