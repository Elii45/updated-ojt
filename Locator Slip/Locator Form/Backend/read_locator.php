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

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid or missing locator slip ID.");
}

$id = intval($_GET['id']);

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
</head>
<body>
    <!-- Locator Header -->
        <div class="header-wrapper">
            <?php include '../../../locatorHeader.html'; ?>
        </div>

    <div class="container">        
        <!-- HEADER -->
        <div class="header">
            <h1>Provincial Information Technology Office</h1>
            <h2 class="subtext">(Office)</h2>
        </div>
        
        <h2>Locator Slip</h2>
        <div class="allcontent">
            <img class="bg-print-image" src="../../../images/body.png" alt="Background">
            <div class="content1">
                <div class="official">
                    <input type="checkbox" id="official" name="official" disabled <?= $row['official'] ? 'checked' : '' ?> />
                    <label for="officialText">Official</label><br>
                </div>

                <div class="date-group">
                    <label for="date">Date:</label>
                    <div class="input-container">
                        <span class="date" id="date" name="date"><?= htmlspecialchars($dateOfFilingFormatted) ?></span>
                        <span class="subtext">(Date of Filing)</span>
                    </div>
                </div>
            </div>
            
            <div class="content2">
                <div class="field-group">
                    <label for="destination">Destination:</label>
                    <span class="value" id="destination" name="destination"><?= htmlspecialchars($row['destination']) ?></span>
                </div>
                <div class="field-group">
                    <label for="purpose">Purpose:</label>
                    <span class="value" id="purpose" name="purpose"><?= htmlspecialchars($row['purpose']) ?></span>
                </div>
            </div>

            <div class="content3">
                <label for="inclDate">Inclusive Dates: </label>
                <span class="value" id="inclDate" name="inclDate"><?= htmlspecialchars($inclusiveDatesFormatted) ?></span>

                <label for="timeDeparture">Departure Time: </label>
                <span class="value" id="timeDeparture" name="timeDeparture"><?= htmlspecialchars($timeOfDepartureFormatted) ?></span>

                <label for="timeArrival">Arrival Time: </label>
                <span class="value" id="timeArrival" name="timeArrival"><?= htmlspecialchars($timeOfArrivalFormatted) ?></span>
            </div>

            <div class="request-group">
                <label for="request">Requested by:</label>
                <span class="value" id="request" name="request"><?= htmlspecialchars(str_replace(';', ',', $row['requested_by'])) ?></span>
            </div>

            <div class="approved-group">
                <label for="request">Approved by:</label>
                <label>ENGR. RHEA LIZA R. VALERIO</label><br>
                <label>Department Head</label>
            </div>

            <div class="button-container">
                <button type="button" class="edit-btn" onclick="location.href='edit_locator.php?id=<?= $id ?>'">Edit</button>
                <button type="button" onclick="window.print()" class="print-btn">Print This Page</button>
            </div>
        </div>
    </div>
    <!-- Footer -->
        <div class="footer-wrapper">
            <?php include '../../../footer.html'; ?>
        </div>
</body>
</html>
