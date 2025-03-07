<?php
header('Content-Type: application/json');

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
while ($row = $result->fetch_assoc()) {
    $offices[] = [
        "office_id" => $row["office_ID"], 
        "office_name" => $row["office_name"]
    ];
}

// Close connection
$conn->close();

// Return JSON data
echo json_encode($offices);
?>
