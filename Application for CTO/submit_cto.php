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
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve values from POST request
    $employee_name = isset($_POST['employeeName']) ? trim($_POST['employeeName']) : '';
    $office_id = isset($_POST['office']) ? trim($_POST['office']) : ''; // This is office_ID
    $position = isset($_POST['position']) ? trim($_POST['position']) : '';
    $filing_date = isset($_POST['filingDate']) ? trim($_POST['filingDate']) : '';
    $earned_hours = isset($_POST['earnedHours']) ? intval($_POST['earnedHours']) : 0;
    $cto_date = isset($_POST['ctoDate']) ? trim($_POST['ctoDate']) : '';
    $applied_hours = isset($_POST['appliedHours']) ? intval($_POST['appliedHours']) : 0;

    // Validate required fields
    if (empty($office_id)) {
        echo json_encode(["status" => "error", "message" => "Office is required"]);
        exit();
    }

    // Fetch the office_name based on office_ID
    $office_name = "";
    $query = "SELECT office_name FROM offices WHERE office_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $office_id);
    $stmt->execute();
    $stmt->bind_result($office_name);
    $stmt->fetch();
    $stmt->close();

    // If office_name is empty, return an error
    if (empty($office_name)) {
        echo json_encode(["status" => "error", "message" => "Invalid Office ID"]);
        exit();
    }

    // Insert data into the database
    $sql = "INSERT INTO cto_requests (employee_name, office, position, filing_date, earned_hours, cto_date, applied_hours) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssisi", $employee_name, $office_name, $position, $filing_date, $earned_hours, $cto_date, $applied_hours);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Record successfully submitted."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
