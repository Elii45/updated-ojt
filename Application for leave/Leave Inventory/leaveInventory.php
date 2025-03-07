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

$sql = "
    SELECT 
        e.id AS employee_id, e.office, e.last_name, e.first_name, e.middle_name, e.filing_date, e.position, e.salary,
        l.type_of_leave, l.vacation_within_philippines, l.vacation_abroad, l.sick_hospital, l.sick_out_patient, 
        l.special_leave_women, l.study_leave_master, l.study_leave_exam, l.monetization, l.terminal_leave, 
        l.working_days, l.inclusive_dates, l.commutation,
        a.as_of, a.vacation_total_earned, a.sick_total_earned, a.vacation_less_application, a.sick_less_application, 
        a.vacation_balance, a.sick_balance, a.recommendation, a.approval_status, a.days_with_pay, a.days_without_pay, 
        a.others_specify, a.disapproved_to
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
