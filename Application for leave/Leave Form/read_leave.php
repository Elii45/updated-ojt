<?php
// read_leave.php

$employeeId = $_GET['id'] ?? null;
$leaveId = $_GET['leave_id'] ?? null;

if (!$employeeId) {
    die("Employee ID is required.");
}

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "ojt";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch personal details
$stmtEmp = $conn->prepare("SELECT * FROM employeedetails WHERE id = ?");
$stmtEmp->bind_param("i", $employeeId);
$stmtEmp->execute();
$resultEmp = $stmtEmp->get_result();
$employee = $resultEmp->fetch_assoc();
$stmtEmp->close();

if (!$employee) {
    die("No matching employee record found.");
}

// Fetch leave details
if (!$leaveId) {
    $stmtLatestLeave = $conn->prepare("SELECT id FROM leavedetails WHERE employee_id = ? ORDER BY id DESC LIMIT 1");
    $stmtLatestLeave->bind_param("i", $employeeId);
    $stmtLatestLeave->execute();
    $resultLatestLeave = $stmtLatestLeave->get_result();
    $latestLeave = $resultLatestLeave->fetch_assoc();
    $stmtLatestLeave->close();
    
    if ($latestLeave) {
        $leaveId = $latestLeave['id'];
    }
}

$leave = null;
if ($leaveId) {
    $stmtLeave = $conn->prepare("SELECT * FROM leavedetails WHERE id = ?");
    $stmtLeave->bind_param("i", $leaveId);
    $stmtLeave->execute();
    $resultLeave = $stmtLeave->get_result();
    $leave = $resultLeave->fetch_assoc();
    $stmtLeave->close();
}

// Fetch approval/action details (your existing code)...

$action = null;

if ($leaveId) {
    $stmtAction = $conn->prepare("SELECT * FROM leaveapproval WHERE employee_id = ? AND leave_id = ?");
    $stmtAction->bind_param("ii", $employeeId, $leaveId);
} else {
    $stmtAction = $conn->prepare("SELECT * FROM leaveapproval WHERE employee_id = ? ORDER BY id DESC LIMIT 1");
    $stmtAction->bind_param("i", $employeeId);
}

$stmtAction->execute();
$resultAction = $stmtAction->get_result();
$action = $resultAction->fetch_assoc();
$stmtAction->close();


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>View Leave Application</title>
    <link rel="stylesheet" href="./styles/leaveApplication.css" />
    <link rel="stylesheet" href="./styles/leaveApplicationPrint.css" />

</head>
<body>
<div class="wrapper">

    <?php include './print/personalDetails.html'; ?>
    <?php include './print/leaveDetails.html'; ?>
    <?php include './print/actionDetails.html'; ?>

</div>
</body>
</html>
