<?php
header('Content-Type: application/json');

// Database connection settings
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "ojt";
$port = 3307;

// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert or update employee_info
        $stmt = $conn->prepare("INSERT INTO employee_info (office, last_name, first_name, middle_name, filing_date, position, salary) 
                                VALUES (?, ?, ?, ?, ?, ?, ?) 
                                ON DUPLICATE KEY UPDATE 
                                filing_date = VALUES(filing_date), 
                                position = VALUES(position), 
                                salary = VALUES(salary)");

        $office = $_POST['office'] ?? '';
        $lastName = $_POST['lastName'] ?? '';
        $firstName = $_POST['firstName'] ?? '';
        $middleName = $_POST['middleName'] ?? '';
        $filingDate = $_POST['filingDate'] ?? '';
        $position = $_POST['position'] ?? '';
        $salary = $_POST['salary'] ?? '';

        $stmt->bind_param("sssssss", $office, $lastName, $firstName, $middleName, $filingDate, $position, $salary);
        $stmt->execute();

        $employee_id = $stmt->insert_id ?: $conn->insert_id;
        $stmt->close();

        // Insert leave_details
        $stmt = $conn->prepare("INSERT INTO leave_details (employee_id, type_of_leave, vacation_within_philippines, vacation_abroad, sick_hospital, sick_out_patient, special_leave_women, study_leave_master, study_leave_exam, monetization, terminal_leave, working_days, inclusive_dates, commutation) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $leaveType = $_POST['leaveType'] ?? '';
        $vacationWithinPH = $_POST['withinPhilippinesDetail'] ?? '';
        $vacationAbroad = $_POST['abroadDetails'] ?? '';
        $sickHospital = $_POST['hospitalDetail'] ?? '';
        $sickOutPatient = $_POST['outPatientDetail'] ?? '';
        $specialLeaveWomen = $_POST['leaveWomen'] ?? '';
        $studyLeaveMaster = isset($_POST['completion']) ? 1 : 0;
        $studyLeaveExam = isset($_POST['exam']) ? 1 : 0;
        $monetization = isset($_POST['monetization']) ? 1 : 0;
        $terminalLeave = isset($_POST['terminal']) ? 1 : 0;
        $workingDays = $_POST['workingDays'] ?? '';
        $inclusiveDates = $_POST['inclDates'] ?? '';
        $commutation = isset($_POST['requested']) ? 'Requested' : 'Not Requested';

        $stmt->bind_param("issssssiiiisss", $employee_id, $leaveType, $vacationWithinPH, $vacationAbroad, $sickHospital, $sickOutPatient, $specialLeaveWomen, $studyLeaveMaster, $studyLeaveExam, $monetization, $terminalLeave, $workingDays, $inclusiveDates, $commutation);
        $stmt->execute();
        $stmt->close();

        // Insert leave_approval
        $stmt = $conn->prepare("INSERT INTO leave_approval (employee_id, as_of, vacation_total_earned, sick_total_earned, vacation_less_application, sick_less_application, vacation_balance, sick_balance, recommendation, approval_status, days_with_pay, days_without_pay, others_specify, disapproved_to) 
                                VALUES (?, CURDATE(), ?, ?, ?, ?, ?, ?, ?, 'Pending', ?, ?, ?, ?)");

        $vacationTotalEarned = $_POST['vacationTotalEarned'] ?? '';
        $sickTotalEarned = $_POST['sickTotalEarned'] ?? '';
        $vacationLessApplication = $_POST['vacationLessApplication'] ?? '';
        $sickLessApplication = $_POST['sickLessApplication'] ?? '';
        $vacationBalance = $_POST['vacationBalance'] ?? '';
        $sickBalance = $_POST['sickBalance'] ?? '';
        $recommendation = isset($_POST['approval']) ? 'For Approval' : (isset($_POST['disapproval']) ? 'For Disapproval' : '');
        $daysWithPay = $_POST['pay'] ?? '';
        $daysWithoutPay = $_POST['withoutPay'] ?? '';
        $othersSpecify = $_POST['othersApproved'] ?? '';
        $disapprovedTo = $_POST['disapprovedTo'] ?? '';

        $stmt->bind_param("isssssssssss", $employee_id, $vacationTotalEarned, $sickTotalEarned, $vacationLessApplication, $sickLessApplication, $vacationBalance, $sickBalance, $recommendation, $daysWithPay, $daysWithoutPay, $othersSpecify, $disapprovedTo);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();

        echo json_encode(["status" => "success", "message" => "Leave application submitted successfully!", "employee_id" => $employee_id]);
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => "Error submitting leave application: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method. Please submit the form."]);
}

// Close connection
$conn->close();
?>
