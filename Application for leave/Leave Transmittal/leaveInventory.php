<?php
$servername = "127.0.0.1"; 
$username = "root";        
$password = "";            
$dbname = "ojt";           
$port = 3307;              

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Set content type to JSON
header('Content-Type: application/json');

// SQL query to fetch employee leave applications including leave_type
$sql = "
    SELECT 
        CONCAT(e.last_name, ', ', e.first_name, ' ', IFNULL(e.middle_name, '')) AS full_name, 
        e.filing_date, 
        l.inclusive_dates,
        l.leave_type
    FROM EmployeeDetails e
    LEFT JOIN LeaveDetails l ON e.id = l.employee_id
    LEFT JOIN LeaveApproval a ON e.id = a.employee_id
";

// Execute query
$result = $conn->query($sql);

if ($result) {
    $leaveApplications = [];
    while ($row = $result->fetch_assoc()) {
        $leaveApplications[] = $row;
    }
    echo json_encode($leaveApplications);
} else {
    echo json_encode(["error" => "Query failed: " . $conn->error]);
}

// Close connection
$conn->close();
?>
