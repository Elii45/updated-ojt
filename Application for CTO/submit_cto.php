<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "ojt";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $employee_name = trim($_POST['employeeName'] ?? '');
    $office = trim($_POST['office'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $filing_date = trim($_POST['filingDate'] ?? '');
    $earned_hours = intval($_POST['earnedHours'] ?? 0);
    $cto_date = trim($_POST['ctoDate'] ?? '');
    $applied_hours = intval($_POST['appliedHours'] ?? 0);

    if (empty($employee_name) || empty($office) || empty($position) || empty($filing_date) || empty($cto_date)) {
        echo json_encode(["status" => "error", "message" => "Missing required fields."]);
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO cto_requests (employee_name, office, position, filing_date, earned_hours, cto_date, applied_hours) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssisi", $employee_name, $office, $position, $filing_date, $earned_hours, $cto_date, $applied_hours);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Submitted successfully",
            "payload" => [
                "id" => $conn->insert_id
            ]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Insert failed: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
