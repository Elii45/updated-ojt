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

// Retrieve form data
$official = isset($_POST['official']) ? 1 : 0;
$date = $_POST['date'];
$destination = $_POST['destination'];
$purpose = $_POST['purpose'];
$inclDate = $_POST['inclDate'];
$timeDeparture = $_POST['timeDeparture'];
$timeArrival = $_POST['timeArrival'];

// ✅ Ensure `request` is always an array
$requesters = isset($_POST['request']) && is_array($_POST['request']) ? $_POST['request'] : [];

if (empty($requesters)) {
    die("Error: No requesters selected.");
}

// ✅ Convert array into a comma-separated string
$requestersStr = implode(", ", $requesters);

// Format times to 12-hour format
$timeDeparture12 = date("h:i A", strtotime($timeDeparture));
$timeArrival12 = date("h:i A", strtotime($timeArrival));

// ✅ Insert into database (store multiple employees in one row)
$sql = "INSERT INTO locator_slip (official, date, destination, purpose, inclusive_dates, time_of_departure, time_of_arrival, requested_by) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isssssss", $official, $date, $destination, $purpose, $inclDate, $timeDeparture12, $timeArrival12, $requestersStr);

if ($stmt->execute()) {
    // ✅ Redirect to print page with form data
    header("Location: locatorSlipPrint.html?official=$official&date=$date&destination=$destination&purpose=$purpose&inclDate=$inclDate&timeDeparture=$timeDeparture12&timeArrival=$timeArrival12&request=" . urlencode($requestersStr));
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
