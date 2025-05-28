<?php
// edit_leave.php

$employeeId = $_GET['employee_id'] ?? null;
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

// Fetch employee details
$stmtEmp = $conn->prepare("SELECT * FROM employeedetails WHERE id = ?");
$stmtEmp->bind_param("i", $employeeId);
$stmtEmp->execute();
$resultEmp = $stmtEmp->get_result();
$employee = $resultEmp->fetch_assoc();

if (!$employee) {
    die("No matching employee record found.");
}

// Fetch leave details (optional)
$leave = null;
if ($leaveId) {
    $stmtLeave = $conn->prepare("SELECT * FROM leavedetails WHERE id = ? AND employee_id = ?");
    $stmtLeave->bind_param("ii", $leaveId, $employeeId);
    $stmtLeave->execute();
    $resultLeave = $stmtLeave->get_result();
    $leave = $resultLeave->fetch_assoc();
    $stmtLeave->close();
}

// Fetch action details (optional)
$action = null;
if ($leaveId) {
    $stmtAction = $conn->prepare("SELECT * FROM leaveapproval WHERE employee_id = ?");
    $stmtAction->bind_param("i", $employeeId);
    $stmtAction->execute();
    $resultAction = $stmtAction->get_result();
    $action = $resultAction->fetch_assoc();
    $stmtAction->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Leave Application</title>
</head>
<body>
<div class="wrapper">
    <form method="POST" action="update_leave.php">
        <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($employeeId); ?>">
        <?php if ($leaveId): ?>
            <input type="hidden" name="leave_id" value="<?php echo htmlspecialchars($leaveId); ?>">
        <?php endif; ?>

        <div id="personalDetails">
            <?php include __DIR__ . '/edit_leave/personal_details/index.php'; ?>
        </div>

        <table class="container-table">
            <tr>
                <td colspan="2" class="title">
                    <h5>6. DETAILS OF APPLICATION</h5>
                </td>
            </tr>
            <tr>
                <td class="divider">
                    <div id="sectionA">
                        <?php include __DIR__ . '/edit_leave/leave_details/index.php'; ?>
                    </div>
                </td>
                <td class="divider">
                    <div id="sectionB">
                        <?php include __DIR__ . '/edit_leave/action_details/index.php'; ?>
                    </div>
                </td>
            </tr>
        </table>

        <div class="buttonGroup">
            <input type="submit" value="Save Changes" />
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        console.log('Form is being submitted');
    });
});
</script>
</body>
</html>
