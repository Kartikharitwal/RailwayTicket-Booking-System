<?php
// creating connection with db
$servername = "localhost";
$username = "root";
$password = "-0987654321`"; // Note: There's a backtick character at the end that might be a typo
$dbname = "railway_db"; // Updated database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // Uncomment this line if you want to check if the connection is successful
    // echo "<script>alert('Connection Successful');</script>";
}
?>