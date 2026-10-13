<?php
    $eventName = filter_input(INPUT_POST, 'event_name');
    $trackName = filter_input(INPUT_POST, 'track_name');
    $eventDate = filter_input(INPUT_POST, 'event_date');
    $notes = filter_input(INPUT_POST, 'notes');

    // Default profileID to 1 (or your active driver/team profile)
    $profileID = 1;

    if ($eventName == NULL || $trackName == NULL || $eventDate == NULL) {
        $error = "Invalid event details. Please fill in all required fields.";
        include('error.php');
    } else {
        require_once('database.php');

        $query = 'INSERT INTO race_events (profileID, eventName, trackName, eventDate, notes)
                  VALUES (:profileID, :eventName, :trackName, :eventDate, :notes)';

        $statement = $db->prepare($query);
        $statement->bindValue(':profileID', $profileID);
        $statement->bindValue(':eventName', $eventName);
        $statement->bindValue(':trackName', $trackName);
        $statement->bindValue(':eventDate', $eventDate);
        $statement->bindValue(':notes', $notes);

        $statement->execute();
        $statement->closeCursor();

        header('Location: events_list.php');
    }
?>