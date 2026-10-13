<?php
    require_once("database.php");

    // Query adjustments joined with session and event details
    $query = 'SELECT p.adjustmentID, p.timeLogged, p.changeMade, p.carHandlingNotes,
                     s.sessionName, s.trackTemp, s.airTemp, e.eventName, e.trackName
              FROM pit_adjustments p
              LEFT JOIN sessions s ON p.sessionID = s.sessionID
              LEFT JOIN race_events e ON p.eventID = e.eventID
              ORDER BY p.timeLogged DESC';

    $statement = $db->prepare($query);
    $statement->execute();
    $adjustments = $statement->fetchAll();
    $statement->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Crew Chief - Pit Adjustments</title>
    <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
</head>
<body>

    <?php include("header.php"); ?>

    <main>
        <h2>Trackside Pit Adjustments</h2>

        <p><a href="add_adjustment_form.php">Log New Pit Adjustment</a> | <a href="index.php">Back to Setup Sheets</a></p>

        <table>
            <tr>
                <th>Time Logged</th>
                <th>Event / Session</th>
                <th>Temps (Air / Track)</th>
                <th>Change Made</th>
                <th>Handling Notes</th>
            </tr>

            <?php foreach ($adjustments as $adj): ?>
                <tr>
                    <td><?php echo htmlspecialchars($adj['timeLogged']); ?></td>
                    <td>
                        <strong><?php echo htmlspecialchars($adj['eventName'] ?? 'N/A'); ?></strong><br />
                        <small><?php echo htmlspecialchars($adj['sessionName'] ?? 'General Session'); ?></small>
                    </td>
                    <td>
                        Air: <?php echo htmlspecialchars($adj['airTemp'] ?? '-'); ?>°F | 
                        Track: <?php echo htmlspecialchars($adj['trackTemp'] ?? '-'); ?>°F
                    </td>
                    <td><?php echo htmlspecialchars($adj['changeMade']); ?></td>
                    <td><?php echo htmlspecialchars($adj['carHandlingNotes'] ?? '-'); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>

    <?php include("footer.php"); ?>

</body>
</html>