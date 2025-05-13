<?php
header("Content-Type: application/json"); // Ensure response is JSON
header("Access-Control-Allow-Origin: *"); // Allow cross-origin requests if needed

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ojt";
$port = 3306; // Your MySQL port

$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Query to fetch employees
$sql = "SELECT employee_lastName, employee_firstName, employee_middleName FROM pito_office";
$result = $conn->query($sql);

$employees = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Ensure middle name is optional
        $middleName = !empty($row['employee_middleName']) ? " " . $row['employee_middleName'] : "";
        $fullName = $row['employee_lastName'] . ", " . $row['employee_firstName'] . $middleName;

        $employees[] = ["name" => trim($fullName)];
    }
} else {
    die(json_encode(["error" => "No employees found"]));
}

echo json_encode($employees);
$conn->close();
?>
