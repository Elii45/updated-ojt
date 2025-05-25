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
        $last = $row['employee_lastName'];
        $first = $row['employee_firstName'];
        $middle = $row['employee_middleName'];
        $middleInitial = $middle ? strtoupper($middle[0]) . '.' : '';
        $fullName = "$last, $first $middleInitial";
        $employees[] = ["name" => $fullName];
    }
}

echo json_encode($employees);
$conn->close();
?>
