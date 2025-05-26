<?php
// edit_locator.php

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "ojt";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("Invalid locator slip ID.");
}

$sql = "SELECT * FROM locator_slip WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Locator slip not found.");
}

$data = $result->fetch_assoc();

$stmt->close();
$conn->close();

// Split requested_by string into array of requesters
$requesters = preg_split('/;\s*/', $data['requested_by'] ?? "", -1, PREG_SPLIT_NO_EMPTY);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Edit Locator Slip #<?php echo htmlspecialchars($id); ?></title>
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <link rel="stylesheet" href="../Style/locatorSlip.css">
</head>

<body>
    <form action="update_locator.php?id=<?php echo $id; ?>" method="POST" id="locatorForm">
        <h2>Edit LOCATOR SLIP</h2>
        <div class="content1">
            <div class="official">
                <input type="checkbox" id="official" name="official" <?php echo !empty($data['official']) ? 'checked' : ''; ?>>
                <label for="official">Official</label><br>
            </div>
            <div class="date-group">
                <label for="date">Date:</label>
                <div class="input-container">
                    <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($data['date']); ?>">
                    <span class="subtext">(Date of Filing)</span>
                </div>
            </div>
        </div>

        <div class="content2">
            <div class="field-group">
                <label for="destination">Destination:</label>
                <input type="text" id="destination" name="destination" value="<?php echo htmlspecialchars($data['destination']); ?>">
            </div>

            <div class="field-group">
                <label for="purpose">Purpose:</label>
                <input type="text" id="purpose" name="purpose" value="<?php echo htmlspecialchars($data['purpose']); ?>">
            </div>
        </div>

        <div class="content3">
            <label for="inclDate">Inclusive Dates:</label>
            <input type="text" id="inclDate" name="inclDate" placeholder="Select Dates" value="<?php echo htmlspecialchars($data['inclusive_dates']); ?>">

            <label for="timeDeparture">Departure Time: </label>
            <input type="time" id="timeDeparture" name="timeDeparture" value="<?php echo date("H:i", strtotime($data['time_of_departure'])); ?>"><br><br>

            <label for="timeArrival">Arrival Time: </label>
            <input type="time" id="timeArrival" name="timeArrival" value="<?php echo date("H:i", strtotime($data['time_of_arrival'])); ?>"><br><br>
        </div>

        <div class="request-group">
            <label>Requested By:</label>
            <div id="dropdown-container">
                <!-- Dropdowns inserted here -->
            </div>

            <button type="button" id="addDropdown" class="addButton" title="Add Requester">
                <span class="icon">＋</span>
            </button>
        </div>

        <input type="submit" id="submit" value="Update" class="submit">
    </form>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#inclDate", {
            mode: "multiple",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            defaultDate: <?php echo json_encode(preg_split('/,\s*/', $data['inclusive_dates'] ?? "")); ?>
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetchEmployees().then(() => {
                const existingRequesters = <?php echo json_encode($requesters); ?>;
                console.log("Existing requesters:", existingRequesters);

                if (existingRequesters.length === 0) {
                    addDropdown(null);
                } else {
                    existingRequesters.forEach((req) => {
                        addDropdown(req);
                    });
                }

                document.getElementById("addDropdown").addEventListener("click", function () {
                    addDropdown(null);
                });
            });
        });

        let employeesList = [];

        async function fetchEmployees() {
            try {
                const response = await fetch("getEmployees.php");
                const data = await response.json();
                if (data.error) {
                    console.error("Error from backend:", data.error);
                    employeesList = [];
                } else {
                    employeesList = data;
                    // Ensure "PITO Office" is in the list
                    if (!employeesList.some(emp => emp.name === "PITO Office")) {
                        employeesList.unshift({ name: "PITO Office" });
                    }
                }
                console.log("Employees fetched:", employeesList);
            } catch (error) {
                console.error("Error fetching employee names:", error);
                // fallback dummy data
                employeesList = [
                    { name: "PITO Office" }
                ];
                console.log("Using fallback employees:", employeesList);
            }
        }

        function populateDropdown(selectElement, selectedValue = "") {
            // Collect all selected values except the current dropdown's selected value
            const selectedValues = Array.from(document.querySelectorAll(".request"))
                .filter(sel => sel !== selectElement)
                .map(sel => sel.value)
                .filter(val => val !== "");

            selectElement.innerHTML = `<option value="" disabled>Select Employee</option>`;

            if (selectedValue) {
                // Add the selectedValue as the first option
                const firstOption = document.createElement("option");
                firstOption.value = selectedValue;
                firstOption.textContent = selectedValue;
                selectElement.appendChild(firstOption);
            }

            employeesList.forEach(employee => {
                // Show option if not selected elsewhere AND not the selectedValue (to avoid duplicate)
                if (employee.name !== selectedValue && !selectedValues.includes(employee.name)) {
                    const option = document.createElement("option");
                    option.value = employee.name;
                    option.textContent = employee.name;
                    selectElement.appendChild(option);
                }
            });

            selectElement.value = selectedValue || "";
        }

        function addDropdown(selectedValue = null) {
            const container = document.getElementById("dropdown-container");

            const newGroup = document.createElement("div");
            newGroup.classList.add("request-group");

            const select = document.createElement("select");
            select.name = "request[]";
            select.required = true;
            select.classList.add("request");

            populateDropdown(select, selectedValue);

            const removeBtn = document.createElement("button");
            removeBtn.textContent = "Remove";
            removeBtn.type = "button";
            removeBtn.classList.add("remove-btn");
            removeBtn.onclick = function () {
                newGroup.remove();
                updateDropdowns();
            };

            newGroup.appendChild(select);
            newGroup.appendChild(removeBtn);
            container.appendChild(newGroup);

            select.addEventListener("change", function () {
                updateDropdowns();
            });

            updateDropdowns();
        }

        function updateDropdowns() {
            // For each dropdown, refresh options considering other dropdowns' selections
            document.querySelectorAll(".request").forEach((select) => {
                const previousValue = select.value;
                populateDropdown(select, previousValue);
            });
        }
    </script>
</body>

</html>
