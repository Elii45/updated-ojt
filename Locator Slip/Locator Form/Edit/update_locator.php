<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "ojt";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$official = isset($_POST['official']) ? 1 : 0;
$date = $_POST['date'];
$destination = $_POST['destination'];
$purpose = $_POST['purpose'];
$inclDate = $_POST['inclDate'];
$timeDeparture = $_POST['timeDeparture'];
$timeArrival = $_POST['timeArrival'];

$originalRequest = $_POST['originalRequest'];
$originalDate = $_POST['originalDate'];

$timeDeparture12 = date("h:i A", strtotime($timeDeparture));
$timeArrival12 = date("h:i A", strtotime($timeArrival));

$requesters = isset($_POST['request']) && is_array($_POST['request']) ? $_POST['request'] : [];

if (empty($requesters)) {
    die("Error: No requesters selected.");
}

$requestersStr = implode(", ", $requesters);

$sql = "UPDATE locator_slip 
        SET official = ?, 
            date = ?, 
            destination = ?, 
            purpose = ?, 
            inclusive_dates = ?, 
            time_of_departure = ?, 
            time_of_arrival = ?, 
            requested_by = ? 
        WHERE requested_by = ? AND date = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isssssssss", 
    $official, 
    $date, 
    $destination, 
    $purpose, 
    $inclDate, 
    $timeDeparture12, 
    $timeArrival12, 
    $requestersStr,
    $originalRequest,
    $originalDate
);

if ($stmt->execute()) {
    header("Location: ../locatorSlipPrint.html?official=$official&date=$date&destination=$destination&purpose=$purpose&inclDate=$inclDate&timeDeparture=$timeDeparture12&timeArrival=$timeArrival12&request=" . urlencode($requestersStr));
    exit();
} else {
    echo "Error updating record: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

