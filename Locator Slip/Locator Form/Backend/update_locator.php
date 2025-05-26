<?php
// update_locator.php

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "ojt";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get ID from GET parameter
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("Invalid locator slip ID.");
}

// Collect POST data safely
$official = isset($_POST['official']) ? 1 : 0;
$date = $_POST['date'] ?? null;
$destination = $_POST['destination'] ?? "";
$purpose = $_POST['purpose'] ?? "";
$inclusive_dates = $_POST['inclDate'] ?? "";
$timeDeparture = $_POST['timeDeparture'] ?? null;
$timeArrival = $_POST['timeArrival'] ?? null;

// Requested By comes as array - join to string separated by semicolon
$requestArray = $_POST['request'] ?? [];
// sanitize each requester name to avoid injection or invalid chars
$requesters = array_map(function($r) use ($conn) {
    return $conn->real_escape_string(trim($r));
}, $requestArray);

$requested_by = implode("; ", $requesters);

// Validate required fields (basic example)
if (!$date) {
    die("Date is required.");
}

// Prepare update statement
$sql = "UPDATE locator_slip SET
    official = ?,
    date = ?,
    destination = ?,
    purpose = ?,
    inclusive_dates = ?,
    time_of_departure = ?,
    time_of_arrival = ?,
    requested_by = ?
    WHERE id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param(
    "isssssssi",
    $official,
    $date,
    $destination,
    $purpose,
    $inclusive_dates,
    $timeDeparture,
    $timeArrival,
    $requested_by,
    $id
);

if ($stmt->execute()) {
    // Success - redirect or show success message
    header("Location: read_locator.php?id=" . $id);
    exit;
} else {
    echo "Error updating record: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
