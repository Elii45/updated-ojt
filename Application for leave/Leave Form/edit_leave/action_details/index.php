<?php
// action_details/index.php

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "ojt";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$employeeId = $_GET['employee_id'] ?? null;

if (!$employeeId) {
    die("Missing employee ID.");
}

// Fetch action details
$sqlAction = "SELECT * FROM leaveapproval WHERE employee_id = ?";
$stmtAction = $conn->prepare($sqlAction);
$stmtAction->bind_param("i", $employeeId);
$stmtAction->execute();
$resultAction = $stmtAction->get_result();
$action = $resultAction->fetch_assoc();

if (!$action) {
    die("No matching action details found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    $updateSql = "UPDATE leaveapproval 
                  SET as_of=?, vacation_total_earned=?, sick_total_earned=?, vacation_leave_balance=?, vacation_less_application=?, 
                      sick_leave_balance=?, sick_less_application=?, 
                      days_with_pay=?, days_without_pay=?, other_days=?, 
                      other_specify=?, disapproved_reason=?
                  WHERE employee_id=?";
    
    $stmtUpdate = $conn->prepare($updateSql);
    $stmtUpdate->bind_param("sdddddddddssi", 
    $asOf, $vacEarned, $sickEarned, $vlBalance, $vlLess, $slBalance, $slLess,
    $withPay, $withoutPay, $otherDays, $otherSpecify, $disapprovedReason,
    $employeeId
);
    $stmtUpdate->execute();

    header("Location: ../leave_details/index.php?employee_id=$employeeId");
    exit;
}
?>

<form method="post" action="">
    <table>
        <tr>
            <td colspan="2" class="title">
                <h5>7. DETAILS OF ACTION ON APPLICATION</h5>
            </td>
        </tr>

        <tr>
            <td class="divider">
                <div class="container4A">
                    <label>7.A CERTIFICATION OF LEAVE CREDITS</label><br>
                    <div class="asOf">
                        <label>As of:</label>
                        <input type="date" name="as_of" value="<?= htmlspecialchars($action['as_of'] ?? '') ?>">
                    </div>
                    <table class="table4">
                        <tr>
                            <th></th>
                            <th>Vacation Leave</th>
                            <th>Sick Leave</th>
                        </tr>
                        <tr>
                        <td>Total Earned</td>
                        <td><input type="text" name="vacationTotalEarned" value="<?= htmlspecialchars($action['vacation_total_earned'] ?? '') ?>"></td>
                        <td><input type="text" name="sickTotalEarned" value="<?= htmlspecialchars($action['sick_total_earned'] ?? '') ?>"></td>
                    </tr>
                        <tr>
                            <td>Less this application</td>
                            <td><input type="text" name="vacation_less_application" value="<?= htmlspecialchars($action['vacation_less_application'] ?? '') ?>"></td>
                            <td><input type="text" name="sick_less_application" value="<?= htmlspecialchars($action['sick_less_application'] ?? '') ?>"></td>
                        </tr>
                        <tr>
                            <td>Balance</td>
                            <td><input type="text" name="vacation_leave_balance" value="<?= htmlspecialchars($action['vacation_leave_balance'] ?? '') ?>"></td>
                            <td><input type="text" name="sick_leave_balance" value="<?= htmlspecialchars($action['sick_leave_balance'] ?? '') ?>"></td>
                        </tr>
                    </table>
                </div>
            </td>

            <td class="divider">
                <div class="container4B">
                    <label>7.B LEAVE ACTION</label><br>
                    <div>
                        <label>Days with pay:</label>
                        <input type="text" name="days_with_pay" value="<?= htmlspecialchars($action['days_with_pay'] ?? '') ?>">
                    </div>
                    <div>
                        <label>Days without pay:</label>
                        <input type="text" name="days_without_pay" value="<?= htmlspecialchars($action['days_without_pay'] ?? '') ?>">
                    </div>
                    <div>
                        <label>Other days:</label>
                        <input type="text" name="other_days" value="<?= htmlspecialchars($action['other_days'] ?? '') ?>">
                    </div>
                    <div>
                        <label>Other (Specify):</label>
                        <input type="text" name="other_specify" value="<?= htmlspecialchars($action['other_specify'] ?? '') ?>">
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="2" class="divider">
                <div class="container4C">
                    <label>Disapproved due to:</label>
                    <input type="text" name="disapproved_reason" value="<?= htmlspecialchars($action['disapproved_reason'] ?? '') ?>">
                </div>
            </td>
        </tr>
    </table>
</form>
