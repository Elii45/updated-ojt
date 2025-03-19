<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ojt";
$port = 3307;

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
$connectionError = false;
if ($conn->connect_error) {
    $connectionError = true;
}

// Get employee data
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

// Close connection
if ($conn) {
    $conn->close();
}

// Get URL parameters
$official = isset($_GET['official']) ? $_GET['official'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';
$destination = isset($_GET['destination']) ? $_GET['destination'] : '';
$purpose = isset($_GET['purpose']) ? $_GET['purpose'] : '';
$inclDate = isset($_GET['inclDate']) ? $_GET['inclDate'] : '';
$timeDeparture = isset($_GET['timeDeparture']) ? $_GET['timeDeparture'] : '';
$timeArrival = isset($_GET['timeArrival']) ? $_GET['timeArrival'] : '';
$request = isset($_GET['request']) ? $_GET['request'] : '';

// Convert date to input format if needed
function formatDateForInput($dateString) {
    if (empty($dateString)) return '';
    
    try {
        $date = new DateTime($dateString);
        return $date->format('Y-m-d');
    } catch (Exception $e) {
        // Try parsing a different format
        $parts = explode(' ', $dateString);
        if (count($parts) >= 3) {
            $month = date_parse($parts[0])['month'];
            $day = intval(str_replace(',', '', $parts[1]));
            $year = intval($parts[2]);
            
            if ($month && $day && $year) {
                return sprintf('%04d-%02d-%02d', $year, $month, $day);
            }
        }
    }
    
    return '';
}

// Convert time to input format
function formatTimeForInput($timeString) {
    if (empty($timeString)) return '';
    
    try {
        $time = DateTime::createFromFormat('h:i A', $timeString);
        if ($time) {
            return $time->format('H:i');
        }
    } catch (Exception $e) {
        // Just return the original if parsing fails
    }
    
    return $timeString;
}

$formattedDate = formatDateForInput($date);
$formattedTimeDeparture = formatTimeForInput($timeDeparture);
$formattedTimeArrival = formatTimeForInput($timeArrival);

// Parse requesters
$requesters = [];
if (!empty($request)) {
    $requesters = explode(', ', $request);
}

// Function to normalize employee names
function normalizeEmployeeName($name) {
    // Check if the name already has a comma (LastName, FirstName format)
    if (strpos($name, ',') !== false) {
        return $name; // Already in correct format
    }
    
    // Handle cases where we have partial names
    $parts = explode(' ', trim($name));
    
    // If it's just a first name and last initial (e.g., "Arthur L.")
    if (count($parts) == 2 && strlen($parts[1]) <= 2 && substr($parts[1], -1) == '.') {
        return $name; // Return as is, will be matched later
    }
    
    // Default case - can't normalize properly
    return $name;
}

// Normalize requester names
foreach ($requesters as $key => $requester) {
    $requesters[$key] = normalizeEmployeeName($requester);
}

// Function to check if a name is a partial match of another name
function isPartialMatch($name1, $name2) {
    // If either name is empty, they don't match
    if (empty($name1) || empty($name2)) {
        return false;
    }
    
    // If they're exactly the same, they match
    if (strtolower($name1) === strtolower($name2)) {
        return true;
    }
    
    // Check if one name is contained within the other
    if (stripos($name1, $name2) !== false || stripos($name2, $name1) !== false) {
        return true;
    }
    
    // Split names into parts
    $parts1 = preg_split('/[,\s]+/', $name1);
    $parts2 = preg_split('/[,\s]+/', $name2);
    
    // Count matching parts
    $matches = 0;
    foreach ($parts1 as $part1) {
        if (empty($part1)) continue;
        foreach ($parts2 as $part2) {
            if (empty($part2)) continue;
            if (strtolower($part1) === strtolower($part2)) {
                $matches++;
            }
        }
    }
    
    // If we have at least 2 matching parts, consider it a match
    return $matches >= 2;
}

// Deduplicate employee list
$uniqueEmployees = [];
$employeeNames = [];

foreach ($employees as $employee) {
    $isDuplicate = false;
    foreach ($employeeNames as $existingName) {
        if (isPartialMatch($employee['name'], $existingName)) {
            $isDuplicate = true;
            break;
        }
    }
    
    if (!$isDuplicate) {
        $uniqueEmployees[] = $employee;
        $employeeNames[] = $employee['name'];
    }
}

$employees = $uniqueEmployees;

// Ensure the original requesters are in the employee list
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
        $employeeNames[] = $requester;
    }
}

// Sort employees alphabetically
usort($employees, function($a, $b) {
    return strcmp($a['name'], $b['name']);
});
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Locator Slip</title>
    <link rel="stylesheet" href="../Style/locatorSlip.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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

        <!-- Dropdown Container -->
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
                                    $selected = false;
                                    if ($employee['name'] === $requester) {
                                        $selected = true;
                                    } else if (isPartialMatch($employee['name'], $requester)) {
                                        $selected = true;
                                    }
                                ?>
                                <option value="<?php echo htmlspecialchars($employee['name']); ?>" 
                                    <?php echo $selected ? 'selected' : ''; ?>>
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

        <!-- Add Button -->
        <button type="button" id="addDropdown" <?php echo (count($requesters) === 1 && $requesters[0] === 'PITO Office') ? 'disabled' : ''; ?>>Add</button>
        
        <div class="button-group">
            <button type="button" class="back-btn" onclick="window.history.back()">Cancel</button>
            <input type="submit" id="submit" value="Update" class="submit">
        </div>
    </form>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="../Script/inclusiveDate.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Store employee data
        window.employeesList = <?php echo json_encode($employees); ?>;
        
        // Add dropdown functionality
        const addButton = document.getElementById("addDropdown");
        if (addButton) {
            addButton.addEventListener("click", addDropdown);
        }
        
        // Attach change listeners to existing dropdowns
        document.querySelectorAll(".request").forEach(select => {
            select.addEventListener("change", handleSelection);
        });
        
        // Function to add a new dropdown
        function addDropdown() {
            const container = document.getElementById("dropdown-container");
            if (!container) return;
            
            const newGroup = document.createElement("div");
            newGroup.classList.add("request-group");
            
            const select = document.createElement("select");
            select.name = "request[]";
            select.required = true;
            select.classList.add("request");
            
            // Add options
            const defaultOption = document.createElement("option");
            defaultOption.value = "";
            defaultOption.textContent = "Select Employee";
            defaultOption.disabled = true;
            defaultOption.selected = true;
            select.appendChild(defaultOption);
            
            // Add all employees
            window.employeesList.forEach(employee => {
                if (!isEmployeeSelected(employee.name)) {
                    const option = document.createElement("option");
                    option.value = employee.name;
                    option.textContent = employee.name;
                    select.appendChild(option);
                }
            });
            
            const removeBtn = document.createElement("button");
            removeBtn.textContent = "Remove";
            removeBtn.type = "button";
            removeBtn.classList.add("remove-btn");
            removeBtn.onclick = function() {
                newGroup.remove();
                updateDropdowns();
            };
            
            newGroup.appendChild(select);
            newGroup.appendChild(removeBtn);
            container.appendChild(newGroup);
            
            select.addEventListener("change", handleSelection);
        }
        
        // Function to check if an employee is already selected
        function isEmployeeSelected(name) {
            const selectedValues = Array.from(document.querySelectorAll(".request"))
                .map(select => select.value)
                .filter(value => value !== "");
                
            return selectedValues.includes(name);
        }
        
        // Function to update dropdowns
        function updateDropdowns() {
            document.querySelectorAll(".request").forEach((select, index) => {
                const previousValue = select.value;
                
                // Clear options except first
                while (select.options.length > 1) {
                    select.remove(1);
                }
                
                // Add PITO Office to first dropdown
                if (index === 0 && !select.querySelector('option[value="PITO Office"]')) {
                    const pitoOption = document.createElement("option");
                    pitoOption.value = "PITO Office";
                    pitoOption.textContent = "PITO Office";
                    select.appendChild(pitoOption);
                }
                
                // Add employee options
                const selectedValues = getSelectedEmployees();
                window.employeesList.forEach(employee => {
                    if (employee.name === previousValue || !selectedValues.includes(employee.name)) {
                        const option = document.createElement("option");
                        option.value = employee.name;
                        option.textContent = employee.name;
                        select.appendChild(option);
                    }
                });
                
                // Restore previous value
                select.value = previousValue;
            });
        }
        
        // Function to get selected employees
        function getSelectedEmployees() {
            return Array.from(document.querySelectorAll(".request"))
                .map(select => select.value)
                .filter(value => value !== "");
        }
        
        // Function to handle selection     
        function handleSelection() {
            const firstDropdown = document.getElementById("request");
            const addButton = document.getElementById("addDropdown");
            if (!firstDropdown || !addButton) return;
            
            if (firstDropdown.value === "PITO Office") {
                addButton.disabled = true;
                document.querySelectorAll(".request-group").forEach((group, index) => {
                    if (index > 0) group.remove();
                });
            } else {
                addButton.disabled = false;
            }
        }
        
        // Make functions available globally
        window.addDropdown = addDropdown;
        window.updateDropdowns = updateDropdowns;
        window.getSelectedEmployees = getSelectedEmployees;
        window.handleSelection = handleSelection;
    });
    </script>
</body>

</html>

