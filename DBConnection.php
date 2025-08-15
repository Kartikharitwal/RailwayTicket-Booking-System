<?php
// creating connection with db
$servername = "localhost";
$username = "root";
$password = ""; // Default password for root in XAMPP is empty
$dbname = "traindb"; // Match the database name you have in phpMyAdmin

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>
