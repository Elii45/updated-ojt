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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Locator Slip Details</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 2rem; background: #f9f9f9; }
        h2 { text-align: center; margin-bottom: 1rem; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        .field { margin-bottom: 1rem; }
        .label { font-weight: bold; display: block; margin-bottom: 0.3rem; }
        .value { padding: 0.5rem; background: #eee; border-radius: 4px; }
        .buttons { margin-top: 1.5rem; text-align: center; }
        button { padding: 0.6rem 1.2rem; font-size: 1rem; border: none; border-radius: 5px; cursor: pointer; }
        button.edit { background-color: #007BFF; color: white; }
        button.back { background-color: #6c757d; color: white; margin-left: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Locator Slip Details</h2>
        <div class="field">
            <input type="checkbox" disabled <?= $row['official'] ? 'checked' : '' ?> /> Official
        </div>

        <div class="field">
            <span class="label">Date of Filing:</span>
            <span class="value"><?= htmlspecialchars($row['date']) ?></span>
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
            <span class="value"><?= htmlspecialchars($row['inclusive_dates']) ?></span>
        </div>
        <div class="field">
            <span class="label">Time of Departure:</span>
            <span class="value"><?= htmlspecialchars($row['time_of_departure']) ?></span>
        </div>
        <div class="field">
            <span class="label">Time of Arrival:</span>
            <span class="value"><?= htmlspecialchars($row['time_of_arrival']) ?></span>
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
