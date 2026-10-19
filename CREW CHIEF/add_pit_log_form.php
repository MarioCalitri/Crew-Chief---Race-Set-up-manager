<?php
    require("database.php");

    $queryEvents = 'SELECT * FROM race_events ORDER BY eventDate DESC';
    $statementEvents = $db->prepare($queryEvents);
    $statementEvents->execute();
    $events = $statementEvents->fetchAll(PDO::FETCH_ASSOC);
    $statementEvents->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Crew Chief - Add Pit Log</title>
    <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
</head>
<body>
    <?php include("header.php"); ?>

    <main class="pitLogFormSection">
    <h1>Record Pit Session</h1>

    <form action="add_pit_log.php" method="post" class="pitLogForm">
        <div class="formRow">
            <label for="event_id">Race Event:</label>
            <select name="event_id" id="event_id" required>
                <?php foreach ($events as $event): ?>
                    <option value="<?php echo $event['eventID']; ?>">
                        <?php echo htmlspecialchars($event['eventName'] . ' - ' . $event['trackName']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="formRow">
            <label for="session_name">Session Name:</label>
            <select name="session_name" id="session_name" required>
                <option value="">-- Select Session --</option>
                <option value="Practice 1">Practice 1</option>
                <option value="Practice 2">Practice 2</option>
                <option value="Practice 3">Practice 3</option>
                <option value="Heat 1">Heat 1</option>
                <option value="Heat 2">Heat 2</option>
                <option value="Consi">Consi</option>
                <option value="Feature">Feature</option>
            </select>
        </div>

        <h3 class="formSectionHeader">Hot Tire Pressures (PSI)</h3>

        <div class="formRow">
            <label for="hot_pressure_lf">LF Hot PSI:</label>
            <input type="number" step="0.5" name="hot_pressure_lf" id="hot_pressure_lf" placeholder="32.5" />
        </div>

        <div class="formRow">
            <label for="hot_pressure_rf">RF Hot PSI:</label>
            <input type="number" step="0.5" name="hot_pressure_rf" id="hot_pressure_rf" placeholder="30.0" />
        </div>

        <div class="formRow">
            <label for="hot_pressure_lr">LR Hot PSI:</label>
            <input type="number" step="0.5" name="hot_pressure_lr" id="hot_pressure_lr" placeholder="15.0" />
        </div>

        <div class="formRow">
            <label for="hot_pressure_rr">RR Hot PSI:</label>
            <input type="number" step="0.5" name="hot_pressure_rr" id="hot_pressure_rr" placeholder="30.0" />
        </div>

        <h3 class="formSectionHeader">Pit Adjustments</h3>

        <div class="formRow fullWidth">
            <label for="adjustments">Adjustments Made:</label>
            <textarea name="adjustments" id="adjustments" rows="3" placeholder="e.g., Added 2 PSI right rear, adjusted wedge 1 full turn in..."></textarea>
        </div>

        <div class="formRow fullWidth">
            <label for="notes">Session Notes:</label>
            <textarea name="notes" id="notes" rows="3" placeholder="Handling notes, driver feedback..."></textarea>
        </div>

        <div class="formRow buttonRow">
            <div></div> 
            <input type="submit" value="Save Pit Log" class="btnSubmit" />
        </div>
    </form>

    <div class="formRow buttonRow">
            <div></div>
            <input type="submit" value="Back To Pit Logs" class="btnSubmit" />
        </div>
</main>

    <?php include("footer.php"); ?>
</body>
</html>