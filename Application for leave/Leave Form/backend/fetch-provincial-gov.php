<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "ojt";
$port = 3307;

// Establish connection
$conn = mysqli_connect($servername, $username, $password, $dbname, $port);

// Check if the connection was successful
if (!$conn) {
    die(json_encode(["error" => "Database connection failed: " . mysqli_connect_error()]));
}

$query = "SELECT employee_firstName, employee_lastName, employee_middleName 
          FROM pito_office WHERE position LIKE '%Provincial Governor%' LIMIT 1";

$result = mysqli_query($conn, $query);

if ($result && $row = mysqli_fetch_assoc($result)) {
    // Combine first, middle, and last names
    $fullName = trim($row["employee_firstName"] . " " . $row["employee_middleName"] . " " . $row["employee_lastName"]);
    echo json_encode(["name" => $fullName]);
} else {
    echo json_encode(["error" => "Provincial Governor not found"]);
}

mysqli_close($conn);
?>