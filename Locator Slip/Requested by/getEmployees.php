<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ojt";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

$sql = "SELECT employee_lastName, employee_firstName, employee_middleName FROM pito_office";
$result = $conn->query($sql);

$employees = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $middleName = !empty($row['employee_middleName']) ? " " . $row['employee_middleName'] : "";
        $fullName = $row['employee_lastName'] . ", " . $row['employee_firstName'] . $middleName;

        $employees[] = ["name" => trim($fullName)];
    }
}

echo json_encode($employees);
$conn->close();
?>
