<?php
header('Content-Type: application/json');

$employeeId = $_GET['employee_id'] ?? null;
$leaveId = $_GET['leave_id'] ?? null;

if (!$employeeId || !$leaveId) {
    echo json_encode(['success' => false, 'message' => 'Missing IDs']);
    exit;
}

$conn = new mysqli("127.0.0.1", "root", "", "ojt", 3307);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed']);
    exit;
}

$sql = "SELECT e.office, e.last_name, e.first_name, e.middle_name, e.filing_date, e.position, e.salary,
               l.leave_type, l.leave_type_others, l.detail_type, l.detail_description, l.working_days, l.inclusive_dates, l.commutation,
               a.as_of, a.vacation_total_earned, a.sick_total_earned, a.vacation_leave_balance, a.vacation_less_application,
               a.sick_leave_balance, a.sick_less_application, a.days_with_pay, a.days_without_pay, a.other_days,
               a.other_specify, a.disapproved_reason
        FROM employeedetails e
        LEFT JOIN leavedetails l ON e.id = l.employee_id AND l.id = ?
        LEFT JOIN leaveapproval a ON a.employee_id = e.id
        WHERE e.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $leaveId, $employeeId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['success' => true, 'data' => $row]);
} else {
    echo json_encode(['success' => false, 'message' => 'No record found']);
}

$stmt->close();
$conn->close();
