<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "ojt";
$port = 3307;

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data safely
$official = isset($_POST['official']) ? 1 : 0;
$date = $_POST['date'] ?? '';
$destination = $_POST['destination'] ?? '';
$purpose = $_POST['purpose'] ?? '';
$inclDate = $_POST['inclDate'] ?? '';
$timeDeparture = $_POST['timeDeparture'] ?? '';
$timeArrival = $_POST['timeArrival'] ?? '';

// Ensure `request` is an array
$requesters = isset($_POST['request']) && is_array($_POST['request']) ? $_POST['request'] : [];

if (empty($requesters)) {
    die("Error: No requesters selected.");
}

// Convert requesters array into comma-separated string
$requestersStr = implode("; ", $requesters);

// Format times to 12-hour format if times are valid
$timeDeparture12 = $timeDeparture ? date("h:i A", strtotime($timeDeparture)) : null;
$timeArrival12 = $timeArrival ? date("h:i A", strtotime($timeArrival)) : null;

// Prepare insert SQL statement
$sql = "INSERT INTO locator_slip (official, date, destination, purpose, inclusive_dates, time_of_departure, time_of_arrival, requested_by) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("isssssss", $official, $date, $destination, $purpose, $inclDate, $timeDeparture12, $timeArrival12, $requestersStr);

if ($stmt->execute()) {
    // Get the last inserted ID
    $new_id = $conn->insert_id;

    // Redirect to the read page with the inserted record's ID
    header("Location: read_locator.php?id=$new_id");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
