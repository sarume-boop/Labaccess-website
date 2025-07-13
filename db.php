<?php
$servername = "localhost";
$username = "root";
$password ="";
$database = "lab_access_system";
$port = 3307; // Add this line

// Create connection with port
$conn = mysqli_connect($servername, $username, $password, $database, $port);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
