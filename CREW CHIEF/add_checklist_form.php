<?php
    require_once("database.php");

    $queryEvents = 'SELECT eventID, eventName, trackName, eventDate FROM race_events ORDER BY eventDate DESC';
    $statement = $db->prepare($queryEvents);
    $statement->execute();
    $events = $statement->fetchAll();
    $statement->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Crew Chief - Add Checklist</title>
    <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
</head>
<body>

    <?php include("header.php"); ?>

    <main>
        <h2>Create New Checklist</h2>

        <form action="add_checklist.php" method="post">

            <label>Race Event:</label>
            <select name="event_id">
                <?php foreach ($events as $event): ?>
                    <option value="<?php echo $event['eventID']; ?>">
                        <?php echo htmlspecialchars($event['eventName'] . ' - ' . $event['trackName']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br />

            <label>Checklist Title:</label>
            <input type="text" name="list_title" placeholder="e.g. Pre-Race Grid Inspection" required /><br />

            <label>Category:</label>
            <input type="text" name="category" placeholder="e.g. Pit Lane, Trailer, Safety" required /><br />

            <label>&nbsp;</label>
            <input type="submit" value="Create Checklist" /><br />

        </form>

        <p><a href="checklists.php">Back to Checklists</a></p>
    </main>

    <?php include("footer.php"); ?>

</body>
</html>