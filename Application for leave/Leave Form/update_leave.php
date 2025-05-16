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
$office = $_POST['office'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$firstName = $_POST['firstName'] ?? '';
$middleName = $_POST['middleName'] ?? '';
$filingDate = $_POST['filingDate'] ?? '';
$position = $_POST['position'] ?? '';
$salary = $_POST['salary'] ?? 0;

$updateEmpSql = "UPDATE employeedetails SET office=?, last_name=?, first_name=?, middle_name=?, filing_date=?, position=?, salary=? WHERE id=?";
$stmtEmp = $conn->prepare($updateEmpSql);
$stmtEmp->bind_param("ssssssdi", $office, $lastName, $firstName, $middleName, $filingDate, $position, $salary, $employeeId);
$stmtEmp->execute();
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

// === ACTION DETAILS UPDATE ===
if ($leaveId) {
    $asOf = $_POST['as_of'] ?? '';
    $vacEarned = $_POST['vacationTotalEarned'] ?? 0;
    $sickEarned = $_POST['sickTotalEarned'] ?? 0;
    $vlBalance = $_POST['vacation_leave_balance'] ?? 0;
    $vlLess = $_POST['vacation_less_application'] ?? 0;
    $slBalance = $_POST['sick_leave_balance'] ?? 0;
    $slLess = $_POST['sick_less_application'] ?? 0;
    $withPay = $_POST['days_with_pay'] ?? 0;
    $withoutPay = $_POST['days_without_pay'] ?? 0;
    $otherDays = $_POST['other_days'] ?? 0;
    $otherSpecify = $_POST['other_specify'] ?? '';
    $disapprovedReason = $_POST['disapproved_reason'] ?? '';

    $updateActionSql = "UPDATE leaveapproval 
                  SET as_of=?, vacation_total_earned=?, sick_total_earned=?, vacation_leave_balance=?, vacation_less_application=?, 
                      sick_leave_balance=?, sick_less_application=?, 
                      days_with_pay=?, days_without_pay=?, other_days=?, 
                      other_specify=?, disapproved_reason=?
                  WHERE employee_id=?";
    
    $stmtAction = $conn->prepare($updateActionSql);
    $stmtAction->bind_param("sdddddddddssi", 
        $asOf, $vacEarned, $sickEarned, $vlBalance, $vlLess, $slBalance, $slLess,
        $withPay, $withoutPay, $otherDays, $otherSpecify, $disapprovedReason,
        $employeeId
    );
    $stmtAction->execute();
    $stmtAction->close();
}

$conn->close();

// Redirect back or to a confirmation page
$printUrl = "/updated-ojt/Application%20for%20leave/Leave%20Form/leaveApplicationPrint.html?employee_id=" . urlencode($employeeId) . "&leave_id=" . urlencode($leaveId);

header("Location: $printUrl");
exit;

?>
