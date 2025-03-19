<?php
header("Content-Type: application/json"); // Ensure response is JSON
header("Access-Control-Allow-Origin: *"); // Allow cross-origin requests if needed

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ojt";
$port = 3307; // Your MySQL port

// Log connection attempt
error_log("Attempting to connect to database: $servername:$port/$dbname");

$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

error_log("Connected successfully to database");

// Query to fetch employees
$sql = "SELECT employee_lastName, employee_firstName, employee_middleName FROM pito_office";
$result = $conn->query($sql);

if (!$result) {
    error_log("Query error: " . $conn->error);
    die(json_encode(["error" => "Query failed: " . $conn->error]));
}

$employees = [];

if ($result->num_rows > 0) {
    error_log("Found " . $result->num_rows . " employees");
    while ($row = $result->fetch_assoc()) {
        // Ensure middle name is optional
        $middleName = !empty($row['employee_middleName']) ? " " . $row['employee_middleName'] : "";
        $fullName = $row['employee_lastName'] . ", " . $row['employee_firstName'] . $middleName;

        $employees[] = ["name" => trim($fullName)];
    }
} else {
    error_log("No employees found in database");
    die(json_encode(["error" => "No employees found"]));
}

error_log("Returning " . count($employees) . " employees");
echo json_encode($employees);
$conn->close();
?>

