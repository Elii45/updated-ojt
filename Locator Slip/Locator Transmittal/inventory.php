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

$sql = "SELECT * FROM locator_slip";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $inventory = [];
    while ($row = $result->fetch_assoc()) {
        $inventory[] = $row;
    }
    echo json_encode($inventory);
} else {
    echo json_encode([]);
}
$conn->close();
?>
