<?php
// DB connection setup
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "ojt";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get id from URL and validate
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid or missing locator slip ID.");
}

$id = intval($_GET['id']);

// Prepare & execute SQL query to fetch the record
$sql = "SELECT * FROM locator_slip WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Locator slip not found.");
}

$row = $result->fetch_assoc();

$stmt->close();
$conn->close();

// Helper function to format inclusive dates into ranges
function formatDateRanges(array $dates): string {
    // Convert strings to timestamps and sort
    $timestamps = array_map('strtotime', array_map('trim', $dates));
    sort($timestamps);

    $ranges = [];
    $start = $timestamps[0];
    $prev = $start;

    for ($i = 1; $i <= count($timestamps); $i++) {
        $current = $timestamps[$i] ?? null;

        // Check if current date is consecutive (prev + 1 day)
        if ($current === $prev + 86400) {
            $prev = $current;
            continue;
        }

        // Format range or single date
        if ($start === $prev) {
            $ranges[] = date('M j', $start);
        } else {
            $ranges[] = date('M j', $start) . '-' . date('j', $prev);
        }

        $start = $current;
        $prev = $current;
    }

    // Append the year from first date (assuming all dates same year)
    $year = date('Y', $timestamps[0]);

    return implode(', ', $ranges) . " $year";
}

// Format the inclusive dates field
$inclusiveDates = explode(',', $row['inclusive_dates']);
$inclusiveDatesFormatted = formatDateRanges($inclusiveDates);

// Format date of filing
$dateOfFilingFormatted = date('M j, Y', strtotime($row['date']));

// Format times with AM/PM
$timeOfDepartureFormatted = date('h:i A', strtotime($row['time_of_departure']));
$timeOfArrivalFormatted = date('h:i A', strtotime($row['time_of_arrival']));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Locator Slip Details</title>
    <link rel="stylesheet" href="../Style/read.css">
    <link rel="stylesheet" href="../Style/print.css">
    <link rel="stylesheet" href="../../footer.html">
    <link rel="stylesheet" href="../../locatorHeader.html">
</head>
<body>
    <div class="container">
        <!-- LOGO -->
    <div class="logo-container">
        <div class="text-section">
            <p>Form No. HRD 12</p>
            <p>Jul 2019</p>
            <p>Rev 03</p>
        </div>
        <img src="../../../images/locatorHeader.png" alt="Office Logo" class="office-logo">
    </div>

    <!-- HEADER -->
    <div class="header">
        <h1>Provincial Information Technology Office</h1>
        <h2 class="subtext">(Office)</h2>
    </div>
        <h2>Locator Slip</h2>
        <div class="field">
            <input type="checkbox" disabled <?= $row['official'] ? 'checked' : '' ?> /> Official
        </div>

        <div class="field">
            <span class="label">Date of Filing:</span>
            <span class="value"><?= htmlspecialchars($dateOfFilingFormatted) ?></span>
        </div>
        <div class="field">
            <span class="label">Destination:</span>
            <span class="value"><?= htmlspecialchars($row['destination']) ?></span>
        </div>
        <div class="field">
            <span class="label">Purpose:</span>
            <span class="value"><?= htmlspecialchars($row['purpose']) ?></span>
        </div>
        <div class="field">
            <span class="label">Inclusive Dates:</span>
            <span class="value"><?= htmlspecialchars($inclusiveDatesFormatted) ?></span>
        </div>
        <div class="field">
            <span class="label">Time of Departure:</span>
            <span class="value"><?= htmlspecialchars($timeOfDepartureFormatted) ?></span>
        </div>
        <div class="field">
            <span class="label">Time of Arrival:</span>
            <span class="value"><?= htmlspecialchars($timeOfArrivalFormatted) ?></span>
        </div>
        <div class="field">
            <span class="label">Requested by:</span>
            <span class="value"><?= htmlspecialchars(str_replace(';', ',', $row['requested_by'])) ?></span>
        </div>

        <div class="buttons">
            <button class="edit" onclick="location.href='edit_locator.php?id=<?= $id ?>'">Edit</button>
            <button class="back" onclick="window.history.back()">Back</button>
        </div>
    </div>
</body>
</html>
