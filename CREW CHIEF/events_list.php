<?php
    require_once("database.php");

    $queryEvents = 'SELECT e.eventID, e.eventName, e.trackName, e.eventDate, e.notes,
         COUNT(s.setupID) AS totalSetups
         FROM race_events e
         LEFT JOIN setups s ON e.eventID = s.eventID
         GROUP BY e.eventID
         ORDER BY e.eventDate DESC';

    $statement = $db->prepare($queryEvents);
    $statement->execute();
    $events = $statement->fetchAll();
    $statement->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Crew Chief - Race Events</title>
    <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
</head>
<body>

    <?php include("header.php"); ?>

    <main>
        <h2>Race Events & Track Sessions</h2>

        <table>
            <tr>
                <th>Event Name</th>
                <th>Track</th>
                <th>Date</th>
                <th>Logged Setups</th>
                <th>Notes</th>
                <th>&nbsp;</th> <!-- delete -->
            </tr>

            <?php foreach ($events as $event): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($event['eventName']); ?></strong></td>
                    <td><?php echo htmlspecialchars($event['trackName']); ?></td>
                    <td><?php echo htmlspecialchars($event['eventDate']); ?></td>
                    <td><?php echo htmlspecialchars($event['totalSetups']); ?></td>
                    <td><?php echo htmlspecialchars($event['notes'] ?? '-'); ?></td>
                    <td>
                        <form action="delete_event.php" method="post">
                            <input type="hidden" name="event_id" value="<?php echo $event['eventID']; ?>" />
                            <input type="submit" value="Delete" />
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p><a href="add_event_form.php">Add New Race Event</a> | <a href="index.php">Back to Setup Sheets</a></p>
    </main>

    <?php include("footer.php"); ?>

</body>
</html>