<?php
// process_edit_leave.php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

$employeeId = $_POST['employee_id'] ?? null;
$leaveId = $_POST['leave_id'] ?? null;

if (!$employeeId) {
    die("Missing employee ID.");
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

// === PERSONAL DETAILS UPDATE ===
$officeId = $_POST['office'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$firstName = $_POST['firstName'] ?? '';
$middleName = $_POST['middleName'] ?? '';
$filingDate = $_POST['filingDate'] ?? '';
$position = $_POST['position'] ?? '';
$salary = $_POST['salary'] ?? 0;

// Fetch office_name from the database using officeId
$sqlOffice = "SELECT office_name FROM offices WHERE office_id = ?";
$stmtOffice = $conn->prepare($sqlOffice);
if (!$stmtOffice) {
    die("Prepare failed: " . $conn->error);
}
$stmtOffice->bind_param("i", $officeId);
$stmtOffice->execute();
$resultOffice = $stmtOffice->get_result();

if ($row = $resultOffice->fetch_assoc()) {
    $officeName = $row['office_name'];
} else {
    die("Error: Office ID not found.");
}
$stmtOffice->close();

// Now update employeedetails with officeName
$updateEmpSql = "UPDATE employeedetails 
                 SET office = ?, last_name = ?, first_name = ?, middle_name = ?, filing_date = ?, position = ?, salary = ? 
                 WHERE id = ?";
$stmtEmp = $conn->prepare($updateEmpSql);
if (!$stmtEmp) {
    die("Prepare failed: " . $conn->error);
}

// Bind parameters (s = string, d = double, i = int)
$stmtEmp->bind_param("ssssssdi", $officeName, $lastName, $firstName, $middleName, $filingDate, $position, $salary, $employeeId);

if (!$stmtEmp->execute()) {
    die("Execute failed: " . $stmtEmp->error);
}

$stmtEmp->close();

// === LEAVE DETAILS UPDATE ===
if ($leaveId) {
    $leaveType = $_POST['leaveType'] ?? '';
    $leaveTypeOthers = $_POST['leaveTypeOthers'] ?? '';
    $detailType = $_POST['detailType'] ?? '';
    $detailDescriptions = $_POST['detailDescription'] ?? [];
$detailType = $_POST['detailType'] ?? '';
$detailDescription = $detailDescriptions[$detailType] ?? '';

    $workingDays = $_POST['working_days'] ?? 0;
    $inclusiveDates = $_POST['inclusive_dates'] ?? '';
    $commutation = $_POST['commutation'] ?? 'notRequested';

    $updateLeaveSql = "UPDATE leavedetails SET leave_type=?, leave_type_others=?, detail_type=?, detail_description=?, working_days=?, inclusive_dates=?, commutation=? WHERE id=? AND employee_id=?";
$stmtLeave = $conn->prepare($updateLeaveSql);
$stmtLeave->bind_param("sssssssii", $leaveType, $leaveTypeOthers, $detailType, $detailDescription, $workingDays, $inclusiveDates, $commutation, $leaveId, $employeeId);
if (!$stmtLeave->execute()) {
    die("Error updating leave details: " . $stmtLeave->error);
}
$stmtLeave->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $leaveId) {
    // Retrieve POST data
    $asOf = $_POST['as_of'] ?? '';
    $vacEarned = floatval($_POST['vacationTotalEarned'] ?? 0);
    $sickEarned = floatval($_POST['sickTotalEarned'] ?? 0);
    $vlBalance = floatval($_POST['vacation_leave_balance'] ?? 0);
    $vlLess = floatval($_POST['vacation_less_application'] ?? 0);
    $slBalance = floatval($_POST['sick_leave_balance'] ?? 0);
    $slLess = floatval($_POST['sick_less_application'] ?? 0);
    $recommendation = $_POST['recommendation'] ?? '';
    $disapprovalDetail = $_POST['disapprovalDetail'] ?? '';
    $withPay = floatval($_POST['days_with_pay'] ?? 0);
    $withoutPay = floatval($_POST['days_without_pay'] ?? 0);
    $otherDays = floatval($_POST['other_days'] ?? 0);
    $otherSpecify = $_POST['other_specify'] ?? '';
    $disapprovedReason = $_POST['disapproved_reason'] ?? '';

    $updateActionSql = "UPDATE leaveapproval 
                  SET as_of=?, vacation_total_earned=?, sick_total_earned=?, vacation_leave_balance=?, vacation_less_application=?, 
                      sick_leave_balance=?, sick_less_application=?, recommendation=?, disapprovalDetail=?, 
                      days_with_pay=?, days_without_pay=?, other_days=?, other_specify=?, disapproved_reason=?
                  WHERE employee_id=? AND leave_id=?";

    $stmtAction = $conn->prepare($updateActionSql);
    $stmtAction->bind_param(
        "sddddddssdddssii", 
        $asOf, $vacEarned, $sickEarned, $vlBalance, $vlLess, $slBalance, $slLess, 
        $recommendation, $disapprovalDetail,
        $withPay, $withoutPay, $otherDays, $otherSpecify, $disapprovedReason,
        $employeeId, $leaveId
    );
    $stmtAction->execute();
    $stmtAction->close();
}

$conn->close();

// Redirect to read_leave.php with the leave ID as query parameter
    header("Location: ./read_leave.php?id=" . urlencode($leaveId));
    exit;

?>
