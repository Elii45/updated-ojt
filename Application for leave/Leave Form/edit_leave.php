<?php
// Leave Form/edit_leave.php

$employeeId = $_GET['employee_id'] ?? null;
$leaveId = $_GET['leave_id'] ?? null;

if (!$employeeId) {
    die("Employee ID is required.");
}
?>

<h2>Edit Leave Application</h2>

<div>
    <h3>Personal Details</h3>
    <?php include __DIR__ . '/edit_leave/personal_details/index.php'; ?>
</div>

<?php if ($leaveId): ?>
    <div>
        <h3>Leave Details</h3>
        <?php include __DIR__ . '/edit_leave/leave_details/index.php'; ?>
    </div>

    <div>
        <h3>Action Details</h3>
        <?php include __DIR__ . '/edit_leave/action_details/index.php'; ?>
    </div>
<?php endif; ?>
