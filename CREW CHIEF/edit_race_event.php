<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    require_once("database.php");

    // Handle form submission to update the event
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $eventID = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);
        $eventName = filter_input(INPUT_POST, 'event_name');
        $trackName = filter_input(INPUT_POST, 'track_name');
        $eventDate = filter_input(INPUT_POST, 'event_date');

        if ($eventID && $eventName && $trackName && $eventDate) {
            $queryUpdate = 'UPDATE race_events 
                            SET eventName = :eventName, trackName = :trackName, eventDate = :eventDate 
                            WHERE eventID = :eventID';
            $stmt = $db->prepare($queryUpdate);
            $stmt->bindValue(':eventName', $eventName);
            $stmt->bindValue(':trackName', $trackName);
            $stmt->bindValue(':eventDate', $eventDate);
            $stmt->bindValue(':eventID', $eventID);
            $stmt->execute();
            $stmt->closeCursor();

            header('Location: race_events.php');
            exit();
        }
    }

    // Fetch the event to edit based on GET ID
    $eventID = filter_input(INPUT_GET, 'event_id', FILTER_VALIDATE_INT);
    if (!$eventID) {
        header('Location: race_events.php');
        exit();
    }

    $queryEvent = 'SELECT * FROM race_events WHERE eventID = :eventID';
    $stmt = $db->prepare($queryEvent);
    $stmt->bindValue(':eventID', $eventID);
    $stmt->execute();
    $event = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    if (!$event) {
        header('Location: race_events.php');
        exit();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Crew Chief - Edit Race Event</title>
    <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
</head>
<body>

    <?php include('header.php'); ?>

    <main>
        <h2>Edit Race Event</h2>
        
        <form action="edit_race_event.php" method="post">
            <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['eventID']); ?>">

            <label>Event Name:</label>
            <input type="text" name="event_name" value="<?php echo htmlspecialchars($event['eventName']); ?>" required /><br />

            <label>Track Name:</label>
            <input type="text" name="track_name" value="<?php echo htmlspecialchars($event['trackName']); ?>" required /><br />

            <label>Event Date:</label>
            <input type="date" name="event_date" value="<?php echo htmlspecialchars($event['eventDate']); ?>" required /><br />

            <label>&nbsp;</label>
            <input type="submit" value="Update Event" /><br />
        </form>

        <div id="buttons">
                <label>&nbsp;</label>
                <form action="race_events.php" method="get" style="display: inline;">
                    <input type="submit" value="Cancel  ➡  Back to Race Events" />
                </form>
            </div>

    </main>

    <?php include('footer.php'); ?>

</body>
</html>