<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    require_once("database.php");

    // Get the log ID from URL
    $logID = filter_input(INPUT_GET, 'log_id', FILTER_VALIDATE_INT);
    if (!$logID) {
        $error = "Invalid pit log ID selected.";
        include('error.php');
        exit();
    }

    // Fetch the specific pit log entry
    $queryLog = 'SELECT * FROM pit_logs WHERE logID = :logID';
    $stmtLog = $db->prepare($queryLog);
    $stmtLog->bindValue(':logID', $logID, PDO::PARAM_INT);
    $stmtLog->execute();
    $pitLog = $stmtLog->fetch(PDO::FETCH_ASSOC);
    $stmtLog->closeCursor();

    if (!$pitLog) {
        $error = "Pit log entry not found.";
        include('error.php');
        exit();
    }

    // Fetch all race events for the dropdown
    $queryEvents = 'SELECT * FROM race_events ORDER BY eventDate DESC';
    $stmtEvents = $db->prepare($queryEvents);
    $stmtEvents->execute();
    $events = $stmtEvents->fetchAll(PDO::FETCH_ASSOC);
    $stmtEvents->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Crew Chief - Update Pit Log</title>
    <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
</head>
<body>
    <?php include("header.php"); ?>

    <main class="pitLogFormSection">
        <h1>Update Pit Session</h1>

        <form action="update_pit_log.php" method="post" class="pitLogForm">
            <input type="hidden" name="log_id" value="<?php echo htmlspecialchars($pitLog['logID']); ?>">

            <div class="formRow">
                <label for="event_id">Race Event:</label>
                <select name="event_id" id="event_id" required>
                    <?php foreach ($events as $event): ?>
                        <option value="<?php echo $event['eventID']; ?>" <?php echo ($event['eventID'] == $pitLog['eventID']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($event['eventName'] . ' - ' . $event['trackName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="formRow">
                <label for="session_name">Session Name:</label>
                <select name="session_name" id="session_name" required>
                    <?php 
                        $sessions = ['Practice 1', 'Practice 2', 'Practice 3', 'Heat 1', 'Heat 2', 'Consi', 'Feature'];
                        foreach ($sessions as $session) {
                            $selected = ($pitLog['sessionName'] == $session) ? 'selected' : '';
                            echo "<option value=\"$session\" $selected>$session</option>";
                        }
                    ?>
                </select>
            </div>

            <h3 class="formSectionHeader">Hot Tire Pressures (PSI)</h3>

            <div class="formRow">
                <label for="hot_pressure_lf">LF Hot PSI:</label>
                <input type="number" step="0.5" name="hot_pressure_lf" id="hot_pressure_lf" value="<?php echo htmlspecialchars($pitLog['hotPressureLF'] ?? ''); ?>" />
            </div>

            <div class="formRow">
                <label for="hot_pressure_rf">RF Hot PSI:</label>
                <input type="number" step="0.5" name="hot_pressure_rf" id="hot_pressure_rf" value="<?php echo htmlspecialchars($pitLog['hotPressureRF'] ?? ''); ?>" />
            </div>

            <div class="formRow">
                <label for="hot_pressure_lr">LR Hot PSI:</label>
                <input type="number" step="0.5" name="hot_pressure_lr" id="hot_pressure_lr" value="<?php echo htmlspecialchars($pitLog['hotPressureLR'] ?? ''); ?>" />
            </div>

            <div class="formRow">
                <label for="hot_pressure_rr">RR Hot PSI:</label>
                <input type="number" step="0.5" name="hot_pressure_rr" id="hot_pressure_rr" value="<?php echo htmlspecialchars($pitLog['hotPressureRR'] ?? ''); ?>" />
            </div>

            <h3 class="formSectionHeader">Pit Adjustments</h3>

            <div class="formRow fullWidth">
                <label for="adjustments">Adjustments Made:</label>
                <textarea name="adjustments" id="adjustments" rows="3"><?php echo htmlspecialchars($pitLog['adjustments'] ?? ''); ?></textarea>
            </div>

            <div class="formRow fullWidth">
                <label for="notes">Session Notes:</label>
                <textarea name="notes" id="notes" rows="3"><?php echo htmlspecialchars($pitLog['notes'] ?? ''); ?></textarea>
            </div>

            <div class="formRow buttonRow">
                <div></div>
                <input type="submit" value="Update Pit Log" class="btnSubmit" />
            </div>
        </form>

        <p><a href="pit_logs.php" class="backLink">Back to Pit Logs</a></p>
    </main>

    <?php include("footer.php"); ?>
</body>
</html>