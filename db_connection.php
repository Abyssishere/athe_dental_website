<?php
// db_connection.php
$servername = "localhost:3309";
$username = "root";
$password = "";
$dbname = "db_mike";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
