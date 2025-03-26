<?php
$host = "localhost"; // Change if using a remote server
$user = "root"; // Default for XAMPP/WAMP
$pass = ""; // Default is empty for XAMPP/WAMP
$dbname = "file_management"; // Your database name

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
