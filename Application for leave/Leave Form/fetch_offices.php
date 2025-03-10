<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "ojt";
$port = 3307;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Fetch office data
$sql = "SELECT office_ID, office_name FROM offices";
$result = $conn->query($sql);

// Store results in an array
$offices = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $offices[] = [
            "office_id" => $row["office_ID"], 
            "office_name" => $row["office_name"]
        ];
    }
} else {
    die(json_encode(["status" => "error", "message" => "No offices found."]));
}

// Close connection
$conn->close();

// Return JSON data
echo json_encode($offices, JSON_PRETTY_PRINT);
?>
