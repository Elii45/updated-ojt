<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "ojt";
$port = 3307;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get and sanitize data from form
$id = isset($_POST['id']) ? $conn->real_escape_string($_POST['id']) : '';
$destination = isset($_POST['destination']) ? $conn->real_escape_string($_POST['destination']) : '';
$purpose = isset($_POST['purpose']) ? $conn->real_escape_string($_POST['purpose']) : '';
$inclDate = isset($_POST['inclDate']) ? $conn->real_escape_string($_POST['inclDate']) : '';
$timeDeparture = isset($_POST['timeDeparture']) ? date("h:i A", strtotime($_POST['timeDeparture'])) : '';
$timeArrival = isset($_POST['timeArrival']) ? date("h:i A", strtotime($_POST['timeArrival'])) : '';
$request = isset($_POST['request']) ? $conn->real_escape_string($_POST['request']) : '';

// Check if ID is provided
if (!empty($id)) {
    // Prepare the update query
    $sql = "UPDATE locator_slip SET 
                destination = ?, 
                purpose = ?, 
                inclusive_dates = ?, 
                time_of_departure = ?, 
                time_of_arrival = ?, 
                requested_by = ? 
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssssssi", $destination, $purpose, $inclDate, $timeDeparture, $timeArrival, $request, $id);
        if ($stmt->execute()) {
            // Redirect to print page with updated data
            header("Location: locatorSlipPrint.html?id=$id&destination=$destination&purpose=$purpose&inclDate=$inclDate&timeDeparture=$timeDeparture&timeArrival=$timeArrival&request=$request");
            exit();
        } else {
            echo "Error updating record: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Error: No ID provided.";
}

$conn->close();
?>
