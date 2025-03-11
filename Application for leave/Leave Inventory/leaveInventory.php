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

// Correct SQL query to fetch necessary fields
$sql = "
    SELECT 
        CONCAT(e.last_name, ', ', e.first_name, ' ', IFNULL(e.middle_name, '')) AS full_name, 
        e.filing_date, 
        l.inclusive_dates
    FROM employee_info e
    LEFT JOIN leave_details l ON e.id = l.employee_id
    LEFT JOIN leave_approval a ON e.id = a.employee_id
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $leaveApplications = [];
    while ($row = $result->fetch_assoc()) {
        $leaveApplications[] = $row;
    }
    echo json_encode($leaveApplications);
} else {
    echo json_encode([]);
}

$conn->close();
?>
