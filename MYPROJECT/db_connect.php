<?php
// Database connection parameters
$servername = "localhost";  // Change to your database server
$username = "root";         // Your database username
$password = "";             // Your database password
$dbname = "project";  // The name of your database

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connected successfully";

// Close the connection when you're done
mysqli_close($conn);
?>
