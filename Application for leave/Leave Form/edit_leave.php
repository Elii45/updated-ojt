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
$port = 3306;

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

<h2>Edit Leave Application</h2>

<form method="POST" action="process_edit_leave.php">
    <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($employeeId); ?>">
    <?php if ($leaveId): ?>
        <input type="hidden" name="leave_id" value="<?php echo htmlspecialchars($leaveId); ?>">
    <?php endif; ?>

    <div>
        <h3>Personal Details</h3>
        <?php
        // Make $employee available inside included file
        include __DIR__ . '/edit_leave/personal_details/index.php';
        ?>
    </div>

    <?php if ($leaveId): ?>
        <div>
            <h3>Leave Details</h3>
            <?php
            // Make $leave available inside included file
            include __DIR__ . '/edit_leave/leave_details/index.php';
            ?>
        </div>

        <div>
            <h3>Action Details</h3>
            <?php
            // Make $action available inside included file
            include __DIR__ . '/edit_leave/action_details/index.php';
            ?>
        </div>
    <?php endif; ?>

    <div style="margin-top: 20px;">
        <button type="submit">Save Changes</button>
    </div>
</form>
