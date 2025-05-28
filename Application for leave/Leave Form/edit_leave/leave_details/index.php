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

$employeeId = $_GET['employee_id'] ?? null;
$leaveId = $_GET['leave_id'] ?? null;

if (!$employeeId || !$leaveId) {
    die("Missing required parameters.");
}

$sqlLeave = "SELECT * FROM leavedetails WHERE id = ? AND employee_id = ?";
$stmtLeave = $conn->prepare($sqlLeave);
$stmtLeave->bind_param("ii", $leaveId, $employeeId);
$stmtLeave->execute();
$resultLeave = $stmtLeave->get_result();
$leave = $resultLeave->fetch_assoc();

if (!$leave) {
    die("No matching leave record found.");
}

// Remove the POST processing logic from here

function isChecked($value, $target) {
    return $value === $target ? 'checked' : '';
}

function escape($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES);
}
?>

<!-- Remove the form tag from here -->
<div class="container3A">
    <label>6.A TYPE OF LEAVE TO BE AVAILED OF</label><br>
    <?php
    $leaveOptions = [
        "vacationLeave" => "Vacation Leave",
        "forceLeave" => "Mandatory/Forced Leave",
        "sickLeave" => "Sick Leave",
        "maternityLeave" => "Maternity Leave",
        "paternityLeave" => "Paternity Leave",
        "specialPrivilege" => "Special Privilege Leave",
        "soloParentLeave" => "Solo Parent Leave",
        "studyLeave" => "Study Leave",
        "vawcLeave" => "10-Day VAWC Leave",
        "rehabPriv" => "Rehabilitation Privilege",
        "specialLeave" => "Special Leave Benefits for Women",
        "specialEmergency" => "Special Emergency Leave",
        "adoptionLeave" => "Adoption Leave",
        "others" => "Others (Specify)"
    ];

    foreach ($leaveOptions as $val => $label) {
        echo "<input type='radio' name='leaveType' value='$val' " . isChecked($leave['leave_type'], $val) . " required> <label>$label</label><br>";
        if ($val === 'others') {
            echo "<input type='text' name='leaveTypeOthers' value='" . escape($leave['leave_type_others']) . "'>";
        }
    }
    ?>
</div>

<div class="container3B">
    <label>6.B DETAILS OF LEAVE</label>

    <!-- Vacation/Special Privilege Leave Details -->
    <div id="vacationDetails">
        <label>In case of vacation/special privilege Leave:</label><br>
        <input type="radio" name="detailType" value="withinPhilippines" <?= isChecked($leave['detail_type'], 'withinPhilippines') ?>> Within Philippines
        <input type="text" name="detailDescription[withinPhilippines]" value="<?= $leave['detail_type'] === 'withinPhilippines' ? escape($leave['detail_description']) : '' ?>"><br>

        <input type="radio" name="detailType" value="abroad" <?= isChecked($leave['detail_type'], 'abroad') ?>> Abroad
        <input type="text" name="detailDescription[abroad]" value="<?= $leave['detail_type'] === 'abroad' ? escape($leave['detail_description']) : '' ?>"><br><br>
    </div>

    <!-- Sick Leave Details -->
    <div id="sickLeaveDetails">
        <label>In case of sick Leave:</label><br>
        <input type="radio" name="detailType" value="hospital" <?= isChecked($leave['detail_type'], 'hospital') ?>> In Hospital (Specify Illness)
        <input type="text" name="detailDescription[hospital]" value="<?= $leave['detail_type'] === 'hospital' ? escape($leave['detail_description']) : '' ?>"><br>

        <input type="radio" name="detailType" value="outPatient" <?= isChecked($leave['detail_type'], 'outPatient') ?>> Out Patient (Specify Illness)
        <input type="text" name="detailDescription[outPatient]" value="<?= $leave['detail_type'] === 'outPatient' ? escape($leave['detail_description']) : '' ?>"><br><br>
    </div>

    <!-- Special Leave Benefits for Women -->
    <div id="womenLeaveDetails">
        <label>In case of Special Leave Benefits for Women:</label><br>
        <label>Specify Illness</label>
        <input type="text" name="detailDescription[womenLeave]" value="<?= $leave['leave_type'] === 'specialLeave' ? escape($leave['detail_description']) : '' ?>"><br>
    </div>

    <!-- Study Leave -->
    <div id="studyLeaveDetails">
        <label>In case of Study Leave:</label><br>
        <input type="radio" name="detailType" value="masters" <?= isChecked($leave['detail_type'], 'masters') ?>> Completion of Master's Degree<br>
        <input type="radio" name="detailType" value="boardExam" <?= isChecked($leave['detail_type'], 'boardExam') ?>> BAR/Board Examination Review<br>
    </div>

    <!-- Other Purposes -->
    <div id="otherPurposes">
        <label>Other Purposes:</label><br>
        <input type="radio" name="detailType" value="monetization" <?= isChecked($leave['detail_type'], 'monetization') ?>> Monetization of Leave Credits<br>
        <input type="radio" name="detailType" value="terminal" <?= isChecked($leave['detail_type'], 'terminal') ?>> Terminal Leave<br>
    </div>
</div>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />

<!-- Inclusive Dates Field -->
<div class="locator-input-group">
    <label for="inclusiveDates" class="locator-input-label">Inclusive Dates:</label>
    <input id="inclusiveDates" name="inclusive_dates" type="text" class="locator-input-field"
        placeholder="Select multiple dates" />
</div>

<!-- Working Days Display -->
<div id="workingDaysDisplay" style="margin-top: 10px; font-weight: bold;">
    Total Days Selected: 0
</div>

<!-- Hidden field to submit working days -->
<input type="hidden" id="working_days" name="working_days" value="0" />

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    const defaultDates = <?= json_encode(explode(",", $leave['inclusive_dates'])) ?>;

    flatpickr("#inclusiveDates", {
        mode: "multiple",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "F j, Y",
        defaultDate: defaultDates,
        onChange: function (selectedDates) {
            const totalDays = selectedDates.length;
            document.getElementById("workingDaysDisplay").textContent = "Total Days Selected: " + totalDays;
            document.getElementById("working_days").value = totalDays;
        }
    });

    // Set initial working days on load
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("workingDaysDisplay").textContent =
            "Total Days Selected: " + defaultDates.length;
        document.getElementById("working_days").value = defaultDates.length;
    });
</script>

Commutation:
<select name="commutation">
    <option value="requested" <?= $leave['commutation'] === 'requested' ? 'selected' : '' ?>>Requested</option>
    <option value="notRequested" <?= $leave['commutation'] === 'notRequested' ? 'selected' : '' ?>>Not Requested</option>
</select><br><br>
