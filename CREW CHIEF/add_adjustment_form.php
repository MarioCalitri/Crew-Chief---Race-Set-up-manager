<?php
    require_once("database.php");

    // Fetch active sessions joined with events
    $querySessions = 'SELECT s.sessionID, s.sessionName, e.eventID, e.eventName, e.trackName
                     FROM sessions s
                     JOIN race_events e ON s.eventID = e.eventID
                     ORDER BY e.eventDate DESC';
    $statement = $db->prepare($querySessions);
    $statement->execute();
    $sessions = $statement->fetchAll();
    $statement->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Crew Chief - Log Pit Adjustment</title>
    <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
</head>
<body>

    <?php include("header.php"); ?>

    <main>
        <h2>Log Pit Adjustment</h2>

        <form action="add_adjustment.php" method="post">

            <label>Session:</label>
            <select name="session_id" required>
                <?php foreach ($sessions as $session): ?>
                    <option value="<?php echo $session['sessionID']; ?>" data-event="<?php echo $session['eventID']; ?>">
                        <?php echo htmlspecialchars($session['eventName'] . ' - ' . $session['sessionName']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br />

            <!-- Hidden field for foreign key eventID -->
            <input type="hidden" name="event_id" value="<?php echo $sessions[0]['eventID'] ?? 1; ?>" />

            <label>Change Made:</label>
            <textarea name="change_made" rows="3" cols="50" placeholder="e.g. Added 1/2 turn cross weight, lowered RF pressure by 0.5 PSI" required></textarea><br />

            <label>Handling Notes:</label>
            <textarea name="handling_notes" rows="3" cols="50" placeholder="e.g. Car was tight on entry, freer off turn 4"></textarea><br />

            <label>&nbsp;</label>
            <input type="submit" value="Log Adjustment" /><br />

        </form>

        <p><a href="pit_adjustments.php">Back to Adjustment Log</a></p>
    </main>

    <?php include("footer.php"); ?>

</body>
</html>