<?php
// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "ojt";
$port = 3307;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start transaction
$conn->begin_transaction();

try {
    // Process form data
    // 1. Personal Details
    $officeId = $_POST['office'];
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $filingDate = $_POST['filingDate'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];

    // Fetch office_name from the database
    $sql = "SELECT office_name FROM offices WHERE office_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $officeId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
    $officeName = $row['office_name']; // Get office_name
    } else {
        die("Error: Office ID not found.");
    }
    // Insert employee details with office_name
    $sql = "INSERT INTO employeedetails (office, last_name, first_name, middle_name, filing_date, position, salary) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssd", $officeName, $lastName, $firstName, $middleName, $filingDate, $position, $salary);
    $stmt->execute();

    
    // Get the employee ID
    $employeeId = $conn->insert_id;
    
    // 2. Leave Details
    $leaveType = $_POST['leaveType'];
    $leaveTypeOthers = isset($_POST['leaveTypeOthers']) && !empty($_POST['leaveTypeOthers']) ? $_POST['leaveTypeOthers'] : null;

    // Get detail type based on selected leave type
    $detailType = null;
    $detailDescription = null;
    
    // Debug information
    error_log("Leave Type: " . $leaveType);
    error_log("POST data: " . print_r($_POST, true));
    
    if ($leaveType == 'vacationLeave' || $leaveType == 'specialPrivilege') {
        if (isset($_POST['detailType'])) {
            $detailType = strtolower(trim($_POST['detailType'])); // Normalize input
            if ($detailType === 'withinphilippines') {
                $detailType = 'withinPhilippines';
                $detailDescription = $_POST['withinPhilippinesDetail'] ?? '';
            } elseif ($detailType === 'abroad') {
                $detailType = 'abroad';
                $detailDescription = $_POST['abroadDetails'] ?? '';
            } else {
                // If none of the expected values match, try detecting based on provided data
                if (!empty($_POST['withinPhilippinesDetail'])) {
                    $detailType = 'withinPhilippines';
                    $detailDescription = $_POST['withinPhilippinesDetail'];
                } elseif (!empty($_POST['abroadDetails'])) {
                    $detailType = 'abroad';
                    $detailDescription = $_POST['abroadDetails'];
                } else {
                    $detailType = 'unknown'; // Fallback in case of missing data
                    $detailDescription = 'No details provided';
                }
            }
        } else {
            $detailType = 'unknown';
            $detailDescription = 'No details provided';
        }
    }
     elseif ($leaveType == 'sickLeave') {
        if (isset($_POST['detailType'])) {
            if (isset($_POST['hospitalDetail']) && !empty($_POST['hospitalDetail'])) {
                $detailType = 'hospital';
                $detailDescription = $_POST['hospitalDetail'];
            } elseif (isset($_POST['outPatientDetail']) && !empty($_POST['outPatientDetail'])) {
                $detailType = 'outPatient';
                $detailDescription = $_POST['outPatientDetail'];
            }
            }
             // If the above checks fail, try checking the form structure differently
        if ($detailType === null && isset($_POST['detailType'])) {
            if (strpos($_POST['detailType'], 'hospital') !== false || 
                strpos(strtolower($_POST['detailType']), 'in hospital') !== false) {
                $detailType = 'hospital';
                // Look for illness specification in various possible field names
                $possibleFields = ['hospitalDetail', 'illness', 'sickLeaveDetail', 'specifyIllness'];
                foreach ($possibleFields as $field) {
                    if (isset($_POST[$field]) && !empty($_POST[$field])) {
                        $detailDescription = $_POST[$field];
                        break;
                    }
                }
            } elseif (strpos($_POST['detailType'], 'outpatient') !== false || 
                     strpos(strtolower($_POST['detailType']), 'out patient') !== false) {
                $detailType = 'outPatient';
                if (isset($_POST['outPatientDetail']) && !empty($_POST['outPatientDetail'])) {
                    $detailDescription = $_POST['outPatientDetail'];
                }
            }
        }
    } elseif ($leaveType == 'specialLeave') {
        if (isset($_POST['leaveWomen']) && !empty($_POST['leaveWomen'])) {
            $detailType = 'leaveWomen';
            $detailDescription = $_POST['leaveWomen'];
        }
    } elseif ($leaveType == 'studyLeave') {
        if (isset($_POST['detailType'])) {
            if ($_POST['detailType'] == '1') {
                $detailType = 'completion';
                $detailDescription = 'Completion of Master\'s Degree';
            } else {
                $detailType = 'exam';
                $detailDescription = 'BAR/Board Examination Review';
            }
        }
    } else {
        // Handle other purposes
        if (isset($_POST['detailType'])) {
            if ($_POST['detailType'] == '1') {
                $detailType = 'monetization';
                $detailDescription = 'Monetization of Leave Credits';
            } else {
                $detailType = 'terminal';
                $detailDescription = 'Terminal Leave';
            }
        }
    }
    
    // Set default values if still null
    if ($detailType === null) {
        $detailType = 'other';
    }
    
    if ($detailDescription === null) {
        $detailDescription = 'No details provided';
    }
    
   // Working Days and Inclusive Dates
$inclusive_dates = '';
$working_days = 0;

// Validate and sanitize inclusive_dates (expecting comma-separated date strings)
if (isset($_POST['inclusive_dates'])) {
    // Remove whitespace and sanitize input string
    $datesInput = trim($_POST['inclusive_dates']);

    // Split into array by comma
    $datesArray = explode(',', $datesInput);

    // Validate each date, only keep valid Y-m-d format dates
    $validDates = [];
    foreach ($datesArray as $date) {
        $date = trim($date);
        $d = DateTime::createFromFormat('Y-m-d', $date);
        if ($d && $d->format('Y-m-d') === $date) {
            $validDates[] = $date;
        }
    }

    // Rebuild inclusive_dates as a comma-separated string of valid dates
    $inclusive_dates = implode(',', $validDates);
}

// Validate working_days as integer
if (isset($_POST['working_days'])) {
    $working_days = intval($_POST['working_days']);
}
    
    // Handle commutation field
    $commutation = 'notRequested'; // Default value
    
    if (isset($_POST['commutation'])) {
        if ($_POST['commutation'] == 'Not Requested') {
            $commutation = 'notRequested';
        } elseif ($_POST['commutation'] == 'Requested') {
            $commutation = 'requested';
        } else {
            $commutation = $_POST['commutation']; // Use as is if it matches enum values
        }
    } elseif (isset($_POST['request'])) {
        if ($_POST['request'] == 'Requested') {
            $commutation = 'requested';
        }
    }
    
    // Insert leave details
    $sql = "INSERT INTO leavedetails (employee_id, leave_type, leave_type_others, detail_type, detail_description, 
            working_days, inclusive_dates, commutation) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $employeeId, $leaveType, $leaveTypeOthers, $detailType, $detailDescription, 
                      $working_days, $inclusive_dates, $commutation);
    $stmt->execute();
    
    $leaveId = $conn->insert_id;
    
    // 3. Action Details
    if (isset($_POST['asOf']) && !empty($_POST['asOf'])) {
        $asOf = date('Y-m-d'); // You can customize this as needed

        $vacationBalance = $_POST['vacationBalance'] ?? 0;
        $vacationLessApplication = $_POST['vacationLessApplication'] ?? 0;
        $sickBalance = $_POST['sickBalance'] ?? 0;
        $sickLessApplication = $_POST['sickLessApplication'] ?? 0;

        $recommendation = $_POST['recommendation'] ?? '';
        $disapprovalDetail = $_POST['disapprovalDetail'] ?? '';
        $pay = $_POST['pay'] ?? 0;
        $withoutPay = $_POST['withoutPay'] ?? 0;
        $otherDays = $_POST['otherDays'] ?? 0;
        $otherSpecify = $_POST['otherSpecify'] ?? '';
        $disapprovedReason = $_POST['disapprovedReason'] ?? '';

        $vacationTotalEarned = $_POST['vacationTotalEarned'] ?? 0;
        $sickTotalEarned = $_POST['sickTotalEarned'] ?? 0;

        $sql = "INSERT INTO leaveapproval 
            (employee_id, leave_id, as_of, vacation_leave_balance, vacation_less_application, 
            sick_leave_balance, sick_less_application, recommendation, disapprovalDetail, 
            days_with_pay, days_without_pay, other_days, other_specify, disapproved_reason, 
            vacation_total_earned, sick_total_earned) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "iisssssssiisssss", 
            $employeeId, $leaveId, $asOf, 
            $vacationBalance, $vacationLessApplication,
            $sickBalance, $sickLessApplication,
            $recommendation, $disapprovalDetail,
            $pay, $withoutPay, $otherDays, $otherSpecify,
            $disapprovedReason, $vacationTotalEarned, $sickTotalEarned
        );
        $stmt->execute();
    }
    
    // Commit transaction
    $conn->commit();

    // Redirect to read_leave.php with the leave ID as query parameter
    header("Location: read_leave.php?id=" . urlencode($leaveId));
    exit;

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
        error_log("Database Error: " . $e->getMessage());
    }

    // Close connection
    $conn->close();
