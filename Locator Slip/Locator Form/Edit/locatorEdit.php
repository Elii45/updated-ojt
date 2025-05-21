<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ojt";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

$connectionError = false;
if ($conn->connect_error) {
    $connectionError = true;
}

$employees = [];
if (!$connectionError) {
    $sql = "SELECT employee_lastName, employee_firstName, employee_middleName FROM pito_office";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $middleName = !empty($row['employee_middleName']) ? " " . $row['employee_middleName'] : "";
            $fullName = $row['employee_lastName'] . ", " . $row['employee_firstName'] . $middleName;
            $employees[] = ["name" => trim($fullName)];
        }
    } else {
        $connectionError = true;
    }
}

if ($conn) {
    $conn->close();
}

$official = isset($_GET['official']) ? $_GET['official'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';
$destination = isset($_GET['destination']) ? $_GET['destination'] : '';
$purpose = isset($_GET['purpose']) ? $_GET['purpose'] : '';
$inclDate = isset($_GET['inclDate']) ? $_GET['inclDate'] : '';
$timeDeparture = isset($_GET['timeDeparture']) ? $_GET['timeDeparture'] : '';
$timeArrival = isset($_GET['timeArrival']) ? $_GET['timeArrival'] : '';
$request = isset($_GET['request']) ? $_GET['request'] : '';

// Include functions
require_once 'functions.php';

// Format dates and times
$formattedDate = formatDateForInput($date);
$formattedTimeDeparture = formatTimeForInput($timeDeparture);
$formattedTimeArrival = formatTimeForInput($timeArrival);

// Parse requesters
$requesters = !empty($request) ? explode(', ', $request) : [];

// Normalize requesters
foreach ($requesters as $key => $requester) {
    $requesters[$key] = normalizeEmployeeName($requester);
}

// Deduplicate employees list
$employees = deduplicateEmployees($employees);

// Add requesters if missing (except 'PITO Office')
foreach ($requesters as $requester) {
    $found = false;
    foreach ($employees as $employee) {
        if (isPartialMatch($employee['name'], $requester)) {
            $found = true;
            break;
        }
    }
    if (!$found && $requester !== 'PITO Office') {
        $employees[] = ["name" => $requester];
    }
}

// Sort employees alphabetically
usort($employees, fn($a, $b) => strcmp($a['name'], $b['name']));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Locator Slip</title>
    <link rel="stylesheet" href="../Style/locatorSlip.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
</head>
<body>
    <div class="header">
        <h2>EDIT LOCATOR SLIP</h2>
    </div>

    <div id="errorMessage" class="error-message">
        <?php if ($connectionError): ?>
            Could not connect to database. Using fallback employee data.
        <?php endif; ?>
    </div>

    <form action="update_locator.php" method="POST">
        <input type="hidden" id="originalRequest" name="originalRequest" value="<?php echo htmlspecialchars($request); ?>">
        <input type="hidden" id="originalDate" name="originalDate" value="<?php echo htmlspecialchars($date); ?>">

        <div class="content1">
            <div class="official">
                <input type="checkbox" id="official" name="official" <?php echo (strtolower($official) === 'yes' || $official === '1') ? 'checked' : ''; ?>>
                <label for="officialText">Official</label><br>
            </div>
            <div class="date-group">
                <label for="date">Date:</label>
                <div class="input-container">
                    <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($formattedDate); ?>">
                    <span class="subtext">(Date of Filing)</span>
                </div>
            </div>
        </div>

        <div class="content2">
            <div class="field-group">
                <label for="destination">Destination:</label>
                <input type="text" id="destination" name="destination" value="<?php echo htmlspecialchars($destination); ?>" required>
            </div>

            <div class="field-group">
                <label for="purpose">Purpose:</label>
                <input type="text" id="purpose" name="purpose" value="<?php echo htmlspecialchars($purpose); ?>" required>
            </div>
        </div>

        <div class="content3">
            <label for="inclDate">Inclusive Dates:</label>
            <input type="text" id="inclDate" name="inclDate" placeholder="Select Dates" value="<?php echo htmlspecialchars($inclDate); ?>" required>

            <label for="timeDeparture">Departure Time: </label>
            <input type="time" id="timeDeparture" name="timeDeparture" value="<?php echo htmlspecialchars($formattedTimeDeparture); ?>" required><br><br>

            <label for="timeArrival">Arrival Time: </label>
            <input type="time" id="timeArrival" name="timeArrival" value="<?php echo htmlspecialchars($formattedTimeArrival); ?>" required><br><br>
        </div>

        <div id="dropdown-container">
            <?php if (!empty($requesters)): ?>
                <?php foreach ($requesters as $index => $requester): ?>
                    <div class="request-group">
                        <label for="request<?php echo $index; ?>"><?php echo $index === 0 ? 'Requested by:' : ''; ?></label>
                        <select id="<?php echo $index === 0 ? 'request' : 'request'.$index; ?>" name="request[]" required class="request">
                            <option value="" disabled>Select Employee</option>
                            <?php if ($index === 0): ?>
                                <option value="PITO Office" <?php echo $requester === 'PITO Office' ? 'selected' : ''; ?>>PITO Office</option>
                            <?php endif; ?>
                            <?php foreach ($employees as $employee): ?>
                                <?php
                                    $selected = ($employee['name'] === $requester) || isPartialMatch($employee['name'], $requester);
                                ?>
                                <option value="<?php echo htmlspecialchars($employee['name']); ?>" <?php echo $selected ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($employee['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($index > 0): ?>
                            <button type="button" class="remove-btn" onclick="this.parentElement.remove(); updateDropdowns();">Remove</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="request-group">
                    <label for="request">Requested by:</label>
                    <select id="request" name="request[]" required>
                        <option value="" disabled selected>Select Employee</option>
                        <option value="PITO Office">PITO Office</option>
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?php echo htmlspecialchars($employee['name']); ?>">
                                <?php echo htmlspecialchars($employee['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>
        </div>

        <button type="button" id="addDropdown" <?php echo (count($requesters) === 1 && $requesters[0] === 'PITO Office') ? 'disabled' : ''; ?>>Add</button>
        
        <div class="button-group">
            <button type="button" class="back-btn" onclick="window.history.back()">Cancel</button>
            <input type="submit" id="submit" value="Update" class="submit">
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="../Script/inclusiveDate.js"></script>
    <script src="locatorSlip.js"></script>
    <script>
        // Pass employeesList for JS from PHP variable
        window.employeesList = <?php echo json_encode($employees); ?>;
    </script>
</body>
</html>
