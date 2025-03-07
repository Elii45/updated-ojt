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
$request = $_POST['request'];

$timeDeparture12 = date("h:i A", strtotime($timeDeparture));  
$timeArrival12 = date("h:i A", strtotime($timeArrival));      

$sql = "INSERT INTO locator_slip (official, date, destination, purpose, inclusive_dates, time_of_departure, time_of_arrival, requested_by) 
        VALUES ('$official', '$date', '$destination', '$purpose', '$inclDate', '$timeDeparture12', '$timeArrival12', '$request')";
        
if ($conn->query($sql) === TRUE) {
    header("Location: locatorSlip.html?status=success"); 
    exit();
} else {
    header("Location: locatorSlip.html?status=error");
    exit();
    }
        
    $conn->close();
?>
