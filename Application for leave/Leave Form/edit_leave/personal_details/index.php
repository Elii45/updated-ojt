<?php
// personal_details/index.php

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

// Fetch employee details
$sqlEmp = "SELECT * FROM employeedetails WHERE id = ?";
$stmtEmp = $conn->prepare($sqlEmp);
$stmtEmp->bind_param("i", $employeeId);
$stmtEmp->execute();
$resultEmp = $stmtEmp->get_result();
$employee = $resultEmp->fetch_assoc();

if (!$employee) {
    die("No matching employee record found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $office = $_POST['office'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $firstName = $_POST['firstName'] ?? '';
    $middleName = $_POST['middleName'] ?? '';
    $filingDate = $_POST['filingDate'] ?? '';
    $position = $_POST['position'] ?? '';
    $salary = $_POST['salary'] ?? 0;

    $updateEmpSql = "UPDATE employeedetails SET office=?, last_name=?, first_name=?, middle_name=?, filing_date=?, position=?, salary=? WHERE id=?";
    $stmtUpdateEmp = $conn->prepare($updateEmpSql);
    $stmtUpdateEmp->bind_param("ssssssdi", $office, $lastName, $firstName, $middleName, $filingDate, $position, $salary, $employeeId);
    $stmtUpdateEmp->execute();

    header("Location: ../leave_details/index.php?employee_id=$employeeId");
    exit;
}
?>

<h3>Edit Personal Details</h3>

<form method="post" action="">
    <table class="container-table">
        <tr>
            <td colspan="2" class="divider">
                <div class="container1">
                    <div class="office">
                        <label>1. OFFICE/DEPARTMENT</label>
                        <select id="officeDropdown" name="office" required>
                            <option value="">Select an Office</option>
                            <option value="<?php echo htmlspecialchars($employee['office']); ?>" selected>
                                <?php echo htmlspecialchars($employee['office']); ?>
                            </option>
                        </select>
                    </div>

                    <div class="name">
                        <div class="name-fields">
                            <label>2. NAME:</label>
                            <div>
                                <label>Last Name:</label>
                                <input type="text" name="lastName" value="<?php echo htmlspecialchars($employee['last_name']); ?>" required>
                            </div>
                            <div>
                                <label>First Name:</label>
                                <input type="text" name="firstName" value="<?php echo htmlspecialchars($employee['first_name']); ?>" required>
                            </div>
                            <div>
                                <label>Middle Name:</label>
                                <input type="text" name="middleName" value="<?php echo htmlspecialchars($employee['middle_name']); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="2" class="divider">
                <div class="container1Date">
                    <label>3. DATE OF FILING</label>
                    <input type="date" name="filingDate" value="<?php echo htmlspecialchars($employee['filing_date']); ?>" required>
                    <label>4. POSITION</label>
                    <input type="text" name="position" value="<?php echo htmlspecialchars($employee['position']); ?>" required>
                    <label>5. SALARY</label>
                    <input type="text" name="salary" value="<?php echo htmlspecialchars($employee['salary']); ?>" required>
                </div>
            </td>
        </tr>
    </table>
</form>

    <script src="dropdown.js"></script>

