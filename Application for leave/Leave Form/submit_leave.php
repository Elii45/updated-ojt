<?php
// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = ""; // Set this if required
$dbname = "ojt";
$port = 3307;

try {
    $pdo = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $officeId = $_POST['office']; // Office_ID from the form
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $filingDate = $_POST['filingDate'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    $leaveType = $_POST['leaveType'];
    $workingDays = $_POST['workingDays'];
    $inclusiveDates = $_POST['inclDates'];
    $commutation = isset($_POST['requested']) ? 'Requested' : 'Not Requested';

    try {
        // Fetch office_name based on office_ID
        $stmt = $pdo->prepare("SELECT office_name FROM offices WHERE office_ID = ?");
        $stmt->execute([$officeId]);
        $officeName = $stmt->fetchColumn();

        if (!$officeName) {
            echo "<script>alert('Invalid Office ID'); window.history.back();</script>";
            exit;
        }

        // Insert employee info with office_name instead of office_ID
        $stmt = $pdo->prepare("INSERT INTO employee_info (office, last_name, first_name, middle_name, filing_date, position, salary) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$officeName, $lastName, $firstName, $middleName, $filingDate, $position, $salary]);
        $employeeId = $pdo->lastInsertId();

        // Insert leave details
        $stmt = $pdo->prepare("INSERT INTO leave_details (employee_id, type_of_leave, working_days, inclusive_dates, commutation) 
                                VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$employeeId, $leaveType, $workingDays, $inclusiveDates, $commutation]);

        // Insert into leave_approval with default values
        $stmt = $pdo->prepare("INSERT INTO leave_approval (employee_id, as_of, vacation_total_earned, sick_total_earned, 
                                vacation_less_application, sick_less_application, vacation_balance, sick_balance, 
                                recommendation, approval_status, days_with_pay, days_without_pay, others_specify, disapproved_to) 
                                VALUES (?, NOW(), 0, 0, 0, 0, 0, 0, NULL, 'Pending', 0, 0, 0, NULL)");
        $stmt->execute([$employeeId]);

        echo "<script>alert('Leave application submitted successfully!'); window.location.href='leaveApplication.html';</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
