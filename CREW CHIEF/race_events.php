<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once("database.php");

    // Handle form submission to add a new event
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_event') {
        $eventName = filter_input(INPUT_POST, 'event_name');
        $trackName = filter_input(INPUT_POST, 'track_name');
        $eventDate = filter_input(INPUT_POST, 'event_date');
        $notes = filter_input(INPUT_POST, 'notes');

        if ($eventName && $trackName && $eventDate) {
            $queryAdd = 'INSERT INTO race_events (eventName, trackName, eventDate, notes) 
                         VALUES (:eventName, :trackName, :eventDate, :notes)';
            $stmt = $db->prepare($queryAdd);
            $stmt->bindValue(':eventName', $eventName);
            $stmt->bindValue(':trackName', $trackName);
            $stmt->bindValue(':eventDate', $eventDate);
            $stmt->bindValue(':notes', $notes);
            $stmt->execute();
            $stmt->closeCursor();

            // Refresh page to clear POST data
            header('Location: race_events.php');
            exit();
        }
    }

    // Handle delete event request
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_event') {
        $eventID = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);
        if ($eventID) {
            $queryDelete = 'DELETE FROM race_events WHERE eventID = :eventID';
            $stmt = $db->prepare($queryDelete);
            $stmt->bindValue(':eventID', $eventID);
            $stmt->execute();
            $stmt->closeCursor();

            header('Location: race_events.php');
            exit();
        }
    }

    // Fetch all race events
    $queryEvents = 'SELECT eventID, eventName, trackName, eventDate 
                    FROM race_events 
                    ORDER BY eventDate DESC';
    $statement = $db->prepare($queryEvents);
    $statement->execute();
    $events = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Crew Chief - Race Events</title>
    <link rel="stylesheet" type="text/css" href="css/crew_chief.css" />
</head>
<body>

    <?php include('header.php'); ?>

    <main>
        <h1>Race Events Management</h1>

        <!-- Add New Event Form -->
        <section class="event-form-section">
            <h2>Add New Race Event</h2>
            <form action="race_events.php" method="post" id="add_event_form">
                <input type="hidden" name="action" value="add_event">

                <label>Event Name:</label>
                <input type="text" name="event_name" placeholder="e.g. Gold Rush 126" required /><br />

                <label>Track Name:</label>
                <input type="text" name="track_name" placeholder="e.g. Flamboro Speedway" required /><br />

                <label for="event_date">Event Date:</label>
                    <input 
                    type="date" 
                    id="event_date" 
                    name="event_date" 
                    required
                    onclick="this.showPicker && this.showPicker()"
                >

                <h3>Notes</h3>
                <textarea name="notes" rows="4" cols="50"><?php echo htmlspecialchars($setup['notes'] ?? ''); ?></textarea><br />

                <label>&nbsp;</label>
                <input type="submit" value="Add Race Event" /><br />
            </form>
        </section>

        <hr />

        <!-- Existing Events Table -->
        <section class="event-list-section">
            <h2>Scheduled Race Events</h2>
            <table>
                <tr>
                    <th>Event ID</th>
                    <th>Event Name</th>
                    <th>Track Name</th>
                    <th>Event Date</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>

                <?php if (empty($events)): ?>
                    <tr>
                        <td colspan="5">No race events logged yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event['eventID'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($event['eventName'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($event['trackName'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($event['eventDate'] ?? ''); ?></td>
                            <td>
                                
                                <form action="edit_race_event.php" method="get" style="display:inline;">
                                    <input type="hidden" name="event_id" value="<?php echo $event['eventID']; ?>">
                                    <input type="submit" value="Edit">
                                </form>

                            </td>
                            <td>
                                <form action="race_events.php" method="post" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                    <input type="hidden" name="action" value="delete_event">
                                    <input type="hidden" name="event_id" value="<?php echo $event['eventID']; ?>">
                                    <input type="submit" value="Delete">
                                </form>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                <?php endif; ?>
                
            </table>
        </section>

            <div id="buttons">
                <label>&nbsp;</label>
                <form action="index.php" method="get" style="display: inline;">
                    <input type="submit" value="DASHBOARD" />
                </form>
            </div>
    </main>

    <?php include('footer.php'); ?>

</body>
</html>