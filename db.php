<?php
$servername = "localhost";
$username = "root";
$password = ""; // default XAMPP MySQL password (empty)
$dbname = "voting_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}
?>